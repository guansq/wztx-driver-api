<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 9:45
 */

namespace app\api\logic;

class PR extends BaseLogic{

    protected $table = 'atw_u9_pr';

    protected $STATUS_ARR = [
        'init' => '初始',
        'hang' => '挂起',
        'inquiry' => '询价中',
        'flow' => '流标',
        'close' => '关闭',
    ];

    /**
     * Author: WILL<314112362@qq.com>
     * Describe:把U9数据初始化到数据库
     */
    function initU9Data(){
        $params = ['StartTime'=>1,'EndTime'=>time()];
        $u9Ret = Model('U9Api', 'logic')->httpGetPr($params);
        if($u9Ret['code'] != 2000){
            return resultArray($u9Ret);
        }
        $u9List = $u9Ret['result']['list'];
        if(empty($u9List)){
            return resultArray(4004);
        }
        return $this->insertToDb($u9List);
    }
    /**
     * Author: WILL<314112362@qq.com>
     * Describe:把U9数据同步到数据库
     */
    function syncU9Data(){
        $u9Ret = Model('U9Api', 'logic')->httpGetPr();
        if($u9Ret['code'] != 2000){
            return resultArray($u9Ret);
        }
        $u9List = $u9Ret['result']['list'];
        if(empty($u9List)){
            return resultArray(4004);
        }
        return $this->insertToDb($u9List);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 插入到数据库
     * @param $u9List
     * @return array
     */
    public function insertToDb($u9List){
        $createdCnt =0;
        foreach($u9List as $k => $v){
            //是否存在
            if(!is_numeric($k)){
                $v = $u9List;
            }
            $status = strstr($v['Status'], '已核准') ? 'init' : 'close';
            $saveDate = [
                'pr_code' => $v['PRNo'],
                'pr_ln' => $v['PRLineNo'],
                'item_code' => $v['ItemCode'],
                'item_name' => $v['ItemName'],
                'item_desc' => $v['ItemDesc'],
                'price_num' => $v['PriceNum'],
                'price_uom' => $v['PriceUOM'],
                'tc_num' => $v['TCNum'],
                'tc_uom' => $v['TCUOM'],
                'pro_no' => $v['ProNo'],
                'req_date' => $v['RequireDate'],
                'pr_date' => $v['PRDate'],
                'u9_status' => $v['Status'],
                'status' => $status,
            ];

            if(!$this->exist($saveDate)){
                //trace('$u9SupModel isUpdate ===================false');
                $createdCnt += $this->isUpdate(false)->data($saveDate, true)->save();
            }
            if(!is_numeric($k)){
                break;
            }
        }
        $ret = [
            'syncTotal' => count($u9List),
            'createdCnt' => $createdCnt
        ];
        return resultArray(2000, '', $ret);
    }
    /**
     * Author: WILL<314112362@qq.com>
     * Time: ${DAY}
     * Describe: 请购单生产询价单
     */
    public function prToInquiry(){
        $prList = self::where(['status' => 'init'])
            ->order('pr_date DESC')
            //->limit(1000)
            ->select();
        if(empty($prList)){
            return resultArray(4004);
        }
        $poLogic = model('PO', 'logic');
        $u9Logic = model('U9Api', 'logic');
        $brandLogic = model('BrandStop', 'logic');
        $itemLogic = model('Item', 'logic');
        $prHangCunt = $toIoCount = $notSupCount = $toPoCount = 0;
        foreach($prList as $pr){
            $pr = $pr->toArray();
            $item = $itemLogic->findByCode($pr['item_code']);
            if(empty($item)){
                $this->updateStatus($pr, 'close');
                $this->updateInquiryWay($pr, '无效料号');
                continue;
            }
            //指定供应商的
            if(empty($pr['is_force_inquiry']) && $pr['is_appoint_sup'] == 1){
                if(empty($pr['appoint_sup_code'])){
                    //未指定供应商 挂起
                    $this->updateStatus($pr, 'hang');
                    $this->updateInquiryWay($pr, 'assign');
                    $prHangCunt++;
                    continue;
                }
                // //已经指定了供应商 下询价单
                $this->placeIo($pr);
                // ['status' => 'inquiry']
                $this->updateStatus($pr, 'inquiry');
                $this->updateInquiryWay($pr, 'assign');
                $toIoCount++;
                continue;
            }

            // 根据品牌判断需不需要暂停 指定供应商?
            if(empty($pr['is_force_inquiry']) && $brandLogic->needPause($pr['item_code'])){
                $this->updateStatus($pr, 'hang');
                $this->updateInquiryWay($pr, 'assign');
                $this->where('id', $pr['id'])->update(['is_appoint_sup' => 1]);
                $prHangCunt++;
                continue;
            }


            //查找料品的供应商
            $supItemArr = model('SupItem', 'logic')->where(['item_code' => $pr['item_code']])->select();
            if(empty($supItemArr)){
                // ['status' => 'hang']  流标
                $this->updateStatus($pr, 'flow');
                $this->updateInquiryWay($pr, 'no_sup');
                trace("料号[$pr[item_code]]无法匹配到供应商");
                $notSupCount++;
                continue;
            }
            //独家采购
            if(count($supItemArr) == 1){
                $supItem = $supItemArr[0];
                $priceInfo = $u9Logic->httpGetPurchaseOrderPrice([
                    'ItemCode' => $supItem['item_code'],
                    'SupCode' => $supItem['sup_code']
                ]);
                //获取报价信息 要包含 报价quote_price、交期promise_date
                if(empty($priceInfo) || $priceInfo['code'] != 2000 || !key_exists('RecentOrderPriceTC', $priceInfo['result'])){
                    //下询价单
                    $this->placeIoToSup($pr, $supItemArr);
                    //['status' => 'inquiry']
                    $this->updateStatus($pr, 'inquiry');
                    $this->updateInquiryWay($pr, 'exclusive');
                    $toIoCount++;
                    continue;
                }

                //-------------
                //有价格的逻辑
                //-------------
                $ioInfo = [
                    'sup_code' => $supItem['sup_code'],
                    'promise_date' => $pr['req_date'],
                    'quote_price' => $priceInfo['result']['RecentOrderPriceTC']
                ];
                // 下采购单
                $poLogic->placePurchOrderToSup($pr, $ioInfo);
                // ['status' => 'close']
                $this->updateStatus($pr, 'close');
                $this->updateInquiryWay($pr, 'have_price');
                $toPoCount++;
                continue;
            }

            //-+-----------------------
            // 以下是充分竞争
            //-+-----------------------
            //获取 近期中标记录
            $lastWIo = model('IO', 'logic')->findLastWinBid($pr['item_code']);
           // //最近有采购
            if(!empty($lastWIo)){
                // 下采购单
                $poLogic->placePurchOrderFromIo($lastWIo);
                //['status' => 'close']
                $this->updateStatus($pr, 'close');
                $this->updateInquiryWay($pr, 'compete');
                $toPoCount++;
                continue;
            }
            //下询价单
            $this->placeIoToSup($pr, $supItemArr);
            //['status' => 'inquiry']
            $this->updateStatus($pr, 'inquiry');
            $this->updateInquiryWay($pr, 'compete');
            $toIoCount++;
        }
        // $prHangCunt = $toIoCount = $notSupCount =$toPoCount= 0;
        $ret = [
            'initPrTotal' => count($prList),
            'prHangCunt' => $prHangCunt,
            'toIoCount' => $toIoCount,
            'notSupCount' => $notSupCount,
            'toPoCount' => $toPoCount,
        ];
        return resultArray(2000, '', $ret);
    }

    /**
     * 判断U9的数据是否存在
     */
    public static function exist($data){
        $count = self::where(['pr_code' => $data['pr_code'], 'item_code' => $data['item_code']])->count();
        return $count == 1 ? true : false;//不存在true 存在false
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe:根据请购单下询价单指定 已經供應商的
     *
     *  pr_code       =>  '请购单号',
     *  io_code       =>  '询价单号',
     *  item_code     =>  '料号',
     *  item_name     =>  '物料名称',
     *  sup_code      =>  '供应商编码',
     *  sup_name      =>  '供应商名称',
     *  promise_date  =>  '承诺交期',
     *  quote_price   =>  '报价价格',
     *  quote_date    =>  '报价日期',
     *  remark        =>  '备注',
     *  winbid_date   =>  '中标时间',
     *  quote_endtime =>  '报价截止日期',
     *  price_num     =>  '计价数量',
     *  price_uom     =>  '计价单位',
     *  tc_num        =>  '交易数量',
     *  tc_uom        =>  '交易单位',
     *  req_date      =>  '需求日期',
     */
    public function placeIo($pr){
        $ioModel = model('IO', 'logic');
        $ioCode = generatOrderCode("IO");
        $sup = model('SupplierInfo', 'logic')->findByCode($pr['appoint_sup_code']);
        $item = model('Item', 'logic')->findByCode($pr['item_code']);
        $endTime = $item['quote_limit']+time();
        $taxRate = empty($sup) ? 0.17 : floatval($sup['tax_rate']);
        $ioData = [
            'pr_id' => $pr['id'],
            'pr_ln' => $pr['pr_ln'],
            'pr_code' => $pr['pr_code'],
            'io_code' => $ioCode,
            'item_code' => $pr['item_code'],
            'item_name' => $pr['item_name'],            //物料名称',
            'sup_code' => $pr['appoint_sup_code'],          //'供应商编码',
            'tax_rate' => $taxRate,   //'供应商税率',
            'sup_name' => $pr['appoint_sup_name'],     //'供应商名称',
            'quote_endtime' => $endTime,        //'报价截止日期',
            'price_num' => $pr['price_num'],       //'计价数量',
            'price_uom' => $pr['price_uom'],       //'计价单位',
            'tc_num' => $pr['tc_num'],          //'交易数量',
            'tc_uom' => $pr['tc_uom'],          //'交易单位',
            'req_date' => $pr['req_date'],        //'需求日期',
            'status' => 'init',                 //'状态',
        ];
        $ioModel->data($ioData, true)->isUpdate(false)->save();
        $this->save(['quote_endtime' => $ioData['quote_endtime'], ['id' => $pr['id']]]);

        //TODO 消息推送
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe:根据请购单下询价单
     * @param $pr
     * @param $supItemArr
     */
    public function placeIoToSup($pr, $supItemArr){
        $ioModel = model('IO', 'logic');
        $item = model('Item', 'logic')->findByCode($pr['item_code']);
        $endTime = $item['quote_limit']+time();
        $ioCode = generatOrderCode();

        foreach($supItemArr as $k => $supItem){
            if(!is_numeric($k)){
                $supItem =$supItemArr;
            }
            $sup = model('SupplierInfo', 'logic')->findByCode($supItem['sup_code']);
            $taxRate = empty($sup) ? 0.17 : floatval($sup['tax_rate']);
            $ioData = [
                'pr_id' => $pr['id'],
                'pr_ln' => $pr['pr_ln'],
                'pr_code' => $pr['pr_code'],
                'io_code' => $ioCode,
                'item_code' => $pr['item_code'],
                'item_name' => $pr['item_name'],       //物料名称',
                'sup_code' => $supItem['sup_code'],   //'供应商编码',
                'sup_name' => $supItem['sup_name'],   //'供应商名称',
                'tax_rate' => $taxRate,   //'供应商税率',
                'quote_endtime' => $endTime,     //'报价截止日期'  ,
                'price_num' => $pr['price_num'],       //'计价数量',
                'price_uom' => $pr['price_uom'],       //'计价单位',
                'tc_num' => $pr['tc_num'],          //'交易数量',
                'tc_uom' => $pr['tc_uom'],          //'交易单位',
                'req_date' => $pr['req_date'],        //'需求日期',
                'status' => 'init',                 //'状态',
            ];
            $ioModel->create($ioData);
            trace($this->getLastSql());
            if(!is_numeric($k)){
                break;
            }
            //TODO 消息推送
        }
        $this->where(['id' => $pr['id']])->update(['quote_endtime' => $endTime]);
    }


    /**
     * Author: WILL<314112362@qq.com>
     * Describe:
     * @param $pr      array  请购单
     * @param $status  string 状态值
     */
    public function updateStatus($pr, $status){
        $this->where(['id' => $pr['id']])->update(['status' => $status]);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe:
     * @param $pr           array  请购单
     * @param $inquiryWay   string 询价方式
     */
    public function updateInquiryWay($pr, $inquiryWay){
        $this->where(['id' => $pr['id']])->update(['inquiry_way' => $inquiryWay]);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 获取需要评标的报价
     */
    public function getNeedValuateBid(){
        $dbRet = $this->where('status', 'in', ['inquiry', 'quoted'])//->where('quote_endtime', '<=', $now)
        ->select();
        if(empty($dbRet)){
            return [];
        }
        return collection($dbRet)->toArray();
    }


}