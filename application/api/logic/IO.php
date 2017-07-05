<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 9:45
 */

namespace app\api\logic;

class IO extends BaseLogic{

    protected $table = 'atw_io';
    protected $STATUS_ARR = [
        'init' => '未报价',
        'quoted' => '已报价',
        'winbid' => '中标',
        'winbid_uncheck'=>'需审核',
        'giveupbid' => '弃标',
        'close' => ' 关闭',
    ];

    /**
     * Author: WILL<314112362@qq.com>
     * Describe:
     * @param $io      array  xx单
     * @param $status  string 状态值
     */
    private function updateStatus($io, $status){
        $this->where(['id' => $io['id']])->update(['status' => $status]);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 查找最进供货的供应商
     * @param string $itemCode 物料编号
     */
    public function findLastWinBid($itemCode = ''){
        // 最近限定日期范围
        $dateFrame = getSysconf('last_pruch_date_frame', 7);
        $timeFrame = time() - $dateFrame*24*60*60;
        return self::where('status', 'winbid')
            ->where('winbid_date', '>=', $timeFrame)
            ->order('winbid_date DESC')
            ->find();
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Time: ${DAY}
     * Describe:
     * @param       $user
     * @param array $paging
     * @return array|int
     *
     * Param {String} status            状态值. init=未报价  quoted=已报价  winbid=中标 close=已关闭.
     *
     * SUCCESS {Array} list            询价单号.
     * SUCCESS {Number} list.id                  询价单id.
     * SUCCESS {String} list.itemCode            料品编号.
     * SUCCESS {String} list.itemName            料品名称.
     * SUCCESS {String} list.priceUom            计价单位.
     * SUCCESS {String} list.priceNum            计价数量.
     * SUCCESS {String} list.tcUom               交易单位.
     * SUCCESS {String} list.reqDate             需求日期.
     * SUCCESS {String} list.inqDate             询价日期.
     * SUCCESS {String} list.quoteEndDate        报价截止日期.
     * SUCCESS {String} list.status              状态值. init=未报价  quoted=已报价  winbid=中标 close=已关闭
     * SUCCESS {String} list.statusStr           状态显示值. init=未报价  quoted=已报价  winbid=中标 close=已关闭
     * SUCCESS {String} [list.promiseDate]       报价承诺交期.
     * SUCCESS {String} [list.price]             报价单价.
     * SUCCESS {String} [list.subTotal]          报价小计.
     * SUCCESS {String} [list.remark]            报价备注.
     */
    function findMyInquiry($user, $search = [], $paging = []){

        $where = ['sup_code' => $user['sup_code']];
        if(key_exists('status', $search)){
            if ($search['status'] == 'quoted'){
                $where ['status'] = ['in',['quoted','winbid_uncheck','winbid','close']];
                $where ['quote_date'] = ['>',0];
            }else{
                $where ['status'] ='init';
            }
        }

        $dbRet = $this->where($where)->page($paging['page'], $paging['pageSize'])->order('create_at DESC')->select();
        $dbCount = $this->where($where)->count();
        $pageTotal = floor($dbCount/$paging['pageSize']) + 1;

        if(empty($dbRet)){
            return $this->resultSet['code'] = 4004;
        }
        $retList = [];
        // 已读的询价单ID
        $ioIds = [];
        foreach($dbRet as $item){
            $ioIds[] = $item['id'];
            $retItem = [
                'id' => $item['id'],
                'itemCode' => $item['item_code'],
                'itemName' => $item['item_name'],
                'priceUom' => $item['price_uom'],
                'priceNum' => $item['price_num'],
                'tcUom' => $item['tc_uom'],
                'reqDate' => date('Y-m-d', $item['req_date']),
                'inqDate' => date('Y-m-d', $item['create_at']),
                'quoteEndDate' => empty($item['quote_endtime']) ? '' : date('Y-m-d', $item['quote_endtime']),
                'status' => $item['status'],
                'statusStr' => $this->STATUS_ARR[$item['status']],
                'promiseDate' => empty($item['promise_date']) ? '' : date('Y-m-d', $item['promise_date']),
                'price' => empty($item['quote_price']) ? '0.00' : number_format($item['quote_price'], 2),
                'subTotal' => number_format($item['price_num']*$item['quote_price'], 2),
                'remark' => empty($item['remark']) ? '' : $item['remark'],
            ];
            $retList[] = $retItem;
        }
        $this->resultSet['code'] = 2000;
        $this->resultSet['result']['list'] = $retList;
        $this->resultSet['result']['page'] = $paging['page'];
        $this->resultSet['result']['pageSize'] = $paging['pageSize'];
        $this->resultSet['result']['pageTotal'] = $pageTotal;
        //更改阅读状态
        $this->isUpdate(true)->where('id', 'IN', $ioIds)->update(['read_at' => time()]);

        return $this->resultSet;
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe:供应商报价
     * Param {Number} id                  .
     * Param {Number} promiseDate         承诺交期(时间戳).
     * Param {Number} price               询价单价.
     * Param {String} remark              备注.
     */


    public function quote($params){

        $io = $this->where('id',$params['id'])->find();
        if (empty($io)){
            return resultArray(4001,'无效的ioId='.$params['id']);
        }
        $total = $this->where('pr_id', $io['pr_id'])->count(); // 询价总数
        $status = $total == 1? 'winbid_uncheck':'quoted';

        $saveData = [
            'promise_date' => $params['promiseDate'],
            'quote_price' => $params['price'],
            'remark' => $params['remark'],
            'quote_date' => time(),
            'status' => $status
        ];

        if(!$this->isUpdate(true)->save($saveData, ['id' => $params['id']])){
            return resultArray(5020);
        };



        // 如果请购单的 供应商已经全部报完价了，则该状态为 已报价
        $this->updatePrStatusById($params['id']);

        return resultArray(2000);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 评标
     */
    public function evaluateBid(){
        $now = time();
        $prLogic = model('PR', 'logic');
        $poLogic = model('PO', 'logic');
        $bidPrs = $prLogic->getNeedValuateBid();
        if(empty($bidPrs)){
            return resultArray(4004);
        }
        $cntPrClose = 0;
        $cntPrFlow = 0;
        foreach($bidPrs as $pr){
            $ioTotal = $this->countIoTotal($pr);    //已经报价的数量
            $ioQuoted = $this->countIoQuoted($pr);  //总共需要报价的数量

            if($pr['quote_endtime'] <= $now){  //时间到评标日期 开始评标

                $bestIo = $this->findBestIo($pr);     //寻找中标报价
                if(empty($bestIo)){ // 没有符合条件的供应商
                    $prLogic->updateStatus($pr, 'flow');
                    $this->updateFlowbid($pr);
                    $cntPrFlow++;
                    continue;
                }

                $prLogic->updateStatus($pr, 'winbid'); //中标 已评标
                $this->updateWinbid($bestIo, $ioTotal == 1);
                $ioTotal > 1 ? $poLogic->placePurchOrderFromIo($bestIo) : null;
                $cntPrClose++;
                continue;

            }

            // 如果供应商全部都报价了。则现在就开始评标
            if($ioTotal == $ioQuoted && $ioQuoted > 0){
                $bestIo = $this->findBestIo($pr);     //寻找中标报价
                if(empty($bestIo)){ // 没有符合条件的供应商 ,按流标处理
                    $prLogic->updateStatus($pr, 'flow');
                    $this->updateFlowbid($pr);
                    $cntPrFlow++;
                    continue;
                }

                $prLogic->updateStatus($pr, 'winbid'); //中标
                $this->updateWinbid($bestIo, $ioTotal == 1);
                $ioTotal > 1 ? $poLogic->placePurchOrderFromIo($bestIo) : null;
                $cntPrClose++;
                continue;
            }
        }

        return resultArray(2000, '', [
            'cntPr' => count($bidPrs),
            'cntPrClose' => $cntPrClose,
            'cntPrFlow ' => $cntPrFlow
        ]);
    }

    /**
     *
     * Author: WILL<314112362@qq.com>
     * Time: ${DAY}
     * Describe: 查找中标的报价
     * @param $io
     */
    private function findBestIo($pr){
        $item = model('Item', 'logic')->findByCode($pr['item_code']);
        if(empty($item)){
            trace("评标异常 无效的item_code:$pr[item_code]", 'error');
            return [];
        }

        //-------------------------------
        // 如果物料是 技术型 型的，就按照价技术型评标标准评标
        //-------------------------------
        if($item['pur_attr'] == 'tech'){
            trace("评标 技术型的，item_code:$item[code]");
            return $this->findBestIoByTech($pr,$item);
        }

        //-------------------------------
        //  充分竞争 型
        //-------------------------------
        if($item['pur_attr'] == 'compete'){
            trace("评标 充分竞争的，item_code:$item[code]");
            return $this->findBestIoByCompete($pr,$item);
        }

        //-------------------------------
        // 单一资源 型
        //-------------------------------
        if($item['pur_attr'] == 'single'){
            trace("评标 单一资源，item_code:$item[code]");
            return $this->findBestIoBySingle($pr,$item);
        }



    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 轮询查找报价的io
     * @param $pr      array PR对象
     * @param $pollCnt integer 轮询次数
     */
    private function queryQuotedIoWithRecurs($pr, $item,$pollCnt = 3){

        $prDate = strtotime(date('Y-m-d', $pr['pr_date']));
        $reqDate = strtotime(date('Y-m-d', $pr['req_date']));
        $durDay = intval(($reqDate - $prDate)/(60*60*24));
        $durDay = ceil(($durDay)*pow(1.1, 3 - $pollCnt));

        // 添加货期同步比
        $futureScale = empty($item['future_scale'])?0:$item['future_scale'];
        $reqDate = $prDate + ceil($durDay*(1+$futureScale))*(60*60*24) ;
        trace('$reqDate=='.date('Y-m-d', $reqDate));
        if($durDay <= 0 || empty($prDate) || empty($reqDate)){
            trace("评标-数据异常：pr.id=$pr[id] pr.pr_date=$pr[pr_date] pr.req_date=$pr[req_date]");
            return [];
        }

        $ios = $this->where(['pr_id' => $pr['id']])
            ->where('status', 'quoted')
            ->where('promise_date', '<=', $reqDate)
            ->order('quote_price')
            ->select();
        if(count($ios) == 0 && $pollCnt > 0){
            $pollCnt--;
            return $this->queryQuotedIoWithRecurs($pr, $item, $pollCnt);
        };
        return $ios;
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 技术评标分数
     * @param     $io
     * @param int $lowestPrice
     * @return float|int
     */
    private function getEvaluateScore($io, $lowestPrice = 0, $item = []){
        $item = empty($item) ? model('Item', 'logic')->findByCode($io['item_code']) : $item;
        $priceWeight = $item['price_weight'] or getSysconf('evaluate_price_weight', 0.55);
        $techWeight = $item['tech_weight'] or getSysconf('evaluate_tech_weight', 0.40);
        //$bizWeight = getSysconf('evaluate_biz_weight', 5);
        $sup = model('SupplierInfo', 'logic')->findByCode($io['sup_code']);
        if(empty($sup)){
            trace("计算评标分数异常：无效的sup_code=$io[sup_cod] io.id=$io[id]", 'error');
            return 0;
        }
        $priceScore = ($lowestPrice/$io['quote_price'])*$priceWeight*100; //价格分=（最低报价/ 自家报价）*$priceWeight
        $techScore = $sup['tech_score']*$techWeight; //技术分 （a+b*20+c*60）/100 *40
        $bizScore = $sup['biz_score']; //商务分 fixme
        return $priceScore + $techScore + $bizScore;
    }


    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 中标更新
     * @param $io
     */
    private function updateWinbid($io, $isSingle = false){
        $this->where(['pr_id' => $io['pr_id']])->update(['status' => 'close']);
        $status = $isSingle ? 'winbid_uncheck' : 'winbid'; //单一资源要进行人工审批
        $this->where(['id' => $io['id']])->update(['status' => $status, 'winbid_date' => time()]);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 流标更新
     * @param $io
     */
    private function updateFlowbid($pr){
        $this->where(['pr_id' => $pr['id']])->update(['status' => 'close']);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe:  查询询价单数量
     * @param $pr
     */
    public function countIoTotal($pr){
        return $this->where('pr_id', $pr['id'])->count();
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe:  查询已报价的询价单数量
     * @param $pr
     */
    public function countIoQuoted($pr){
        return $this->where('pr_id', $pr['id'])->where('status', 'quoted')->count();
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 查询未读单数量
     * @param $user
     */
    public function countUnreadMsg($user){
        $cnt = $this->where('read_at', NULL)->where('sup_code', $user['sup_code'])->count();
        //dd($this->getLastSql());
        return $cnt;
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 如果请购单的 供应商已经全部报完价了，则该状态为 已报价
     * @param $id
     */
    public function updatePrStatusById($id){
        $dbRet = $this->field('pr_id')->where('id', $id)->group('pr_id')->find();
        $count = $this->where('pr_id', $dbRet['pr_id'])->where('status', 'init')->count();
        if($count == 0){
            model('PR', 'logic')->updateStatus(['id' => $dbRet['pr_id']], 'quoted');
        }

        return $count;
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 按照技术型评标
     * @param $pr
     * @param $item
     * @return array|mixed
     *
     */
    public function findBestIoByTech($pr, $item){

        $ioList = $this->queryQuotedIoWithRecurs($pr, $item,3);
        if(empty($ioList)){
            return [];
        }
        if(count($ioList) == 1){
            return $ioList[0];
        }
        $bastIo = $ioList[0];

        $lowestPrice = $bastIo['quote_price']; // 最低报价
        $bastScore = 0;
        foreach($ioList as $io){
            $score = $this->getEvaluateScore($io, $lowestPrice, $item);
            if($score > $bastScore){
                $bastIo = $io;
                $bastScore = $score;
            }
        }
        return $bastIo;
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Time: ${DAY}
     * Describe:充分竞争 评标
     * @param $pr
     * @param $item
     */
    public  function findBestIoByCompete($pr, $item){

        $prDate = strtotime(date('Y-m-d', $pr['pr_date']));
        $reqDate = strtotime(date('Y-m-d', $pr['req_date']));
        $durDay = intval(($reqDate - $prDate)/(60*60*24));
        // 添加货期让步比
        $futureScale = empty($item['future_scale'])?0:$item['future_scale'];
        $reqDate = $prDate + ceil($durDay*(1+$futureScale))*(60*60*24) ;
        trace('充分竞争 评标 $reqDate=='.date('Y-m-d', $reqDate));
        $io = $this->where(['pr_id' => $pr['id']])
            ->where('status', 'quoted')
            ->where('promise_date', '<=', $reqDate)
            ->order('quote_price')
            ->find();
        return $io;
    }

    /** FIXME
     * Author: WILL<314112362@qq.com>
     * Describe: 单一资源型 评标
     * @param $pr
     * @param $item
     */

    public function findBestIoBySingle($pr, $item){

        trace('单一资源型 评标 ');
        $io = $this->where(['pr_id' => $pr['id']])
            ->where('status', 'quoted')
            ->order('quote_price')
            ->find();
        return $io;
    }


}