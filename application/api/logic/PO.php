<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 9:45
 */

namespace app\api\logic;

class PO extends BaseLogic{

    protected $table = 'atw_po';

    const STATUS_ARR = [
        'init' => '待确认',
        'sup_cancel' => '供应商取消',
        'sup_edit' => '供应商修改',
        'atw_sure' => '安特威确定',
        'sup_sure' => '供应商确定',
        'upload_contract' => '供应商已经上传合同',
        'contract_pass' => '合同审核通过',
        'contract_refuse' => '合同审核拒绝',
        'executing' => '待送货',
        'finish' => '结束',
    ];


    /**
     * Author: WILL<314112362@qq.com>
     * Describe:把U9数据同步到数据库 查询订单执行情况
     *
     * @apiSuccess {String}  list.DocNo     采购订单号
     * @apiSuccess {String}  list.DocLineNo    采购单据行号
     * @apiSuccess {String}  list.SupCode    供应商代码
     * @apiSuccess {Number}  list.SupName    供应商名称
     * @apiSuccess {String}  list.ItemCode    料品编码
     * @apiSuccess {Number}  list.ItemName    料品名称
     * @apiSuccess {Number}  list.ItemSPECS    料品规格
     * @apiSuccess {Number}  list.DocStatus    单据状态（已审核、已关闭）
     * @apiSuccess {Number}  list.ArrQTY    到货数量
     */
    function syncU9State($param = ['code' => '']){
        $where = empty($param['code']) ? [] : ['order_code' => $param['code']];
        $poList = $this->where($where)->where('status', '<>', 'close')->where('order_code', 'NOT NULL')->select();
        $orderCount = count($poList);
        $updatePOCount = $updatePICount = 0;
        if($orderCount == 0){
            return resultArray(4004);
        }
        foreach($poList as $po){

            $u9Ret = Model('U9Api', 'logic')->httpGetPoStatus($po);
            //$u9Ret = $this->mku9PoState(4);
            if($u9Ret['code'] != 2000){
                return resultArray($u9Ret);
            }
            $poState = $u9Ret['result']['list'];
            $updateData = [
                'last_sync_time' => time()
            ];
            $this->isUpdate(true)->where('id', $po['id'])->update($updateData);

            if(empty($poState)){
                continue;
            }

            $poStateArr = [];
            foreach($poState as $k => $v){
                if(!is_numeric($k)){ // 只返回一条数据
                    $poStateArr = [$poState['ItemCode'] => $poState];
                    break;
                }else{
                    $poStateArr[$v['ItemCode']] = $v;
                }
            }
            $piLogic = model('POItem', 'logic');
            $piList = $piLogic->where('po_id', $po['id'])->select();
            $piList = collection($piList)->toArray();
            $poStatus = null;
            foreach($piList as $k => $pi){

                if(!is_numeric($k)){ // 单个
                    $pi = $piList;
                }

                if(!key_exists($pi['item_code'], $poStateArr)){
                    continue;
                }
                $poState = $poStateArr[$pi['item_code']];
                $poStatus = $poState['DocStatus'];
                $updateData = [
                    'arv_goods_num' => $poState['AryQty'],
                    'pro_goods_num' => $poState['NotAryQty'],
                    'u9_status' => $poStatus,
                    'last_sync_time' => time()
                ];
                $piLogic->isUpdate()->where('id', $pi['id'])->update($updateData);
                $updatePICount++;
            }

            $updateData = [
                'last_sync_time' => time()
            ];
            if(strstr($poStatus, '关闭')){
                $updateData['status'] = 'finish';
            }

            $this->isUpdate(true)->where('id', $po['id'])->update($updateData);
            $updatePOCount++;
        }
        $ret = [
            'poTotal' => $orderCount,
            'updatePOCount' => $updatePOCount,
            'updatePICount' => $updatePICount
        ];
        return resultArray(2000, '', $ret);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe:
     * @param $order      array  xx单
     * @param $status     string 状态值
     */
    public function updateStatus($order, $status){
        $this->where(['id' => $order['id']])->update(['status' => $status]);
    }


    /**
     * Author: WILL<314112362@qq.com>
     * Describe:根据请购单下采购单
     * $ioInfo 包含 报价、交期
     */
    public function placePurchOrderToSup($pr, $ioInfo){
        $now = time();
        $sup = model('SupplierInfo', 'logic')->findByCode($ioInfo['sup_code']);
        $taxRate = empty($sup) ? 0.17 : floatval($sup['tax_rate']);
        /*$poData = [
            'pr_code' => $pr['pr_code'],
            'sup_code' => $ioInfo['sup_code'],
            'is_include_tax' => 1,      //是否含税
            'status' => 'init',
            'create_at' => $now,
            'update_at' => $now,
        ];
        $poId = $this->insertOrGetId($poData);
        if(!$poId){
            return resultArray(5000);
        };*/
        $poItemData = [
            'po_id' => null,
            'po_code' => null,
            'item_code' => $pr['item_code'],
            'item_name' => $pr['item_name'],
            'sup_code' => $ioInfo['sup_code'],
            'sup_name' => $sup['name'],
            'price_num' => $pr['price_num'],
            'price_uom' => $pr['price_uom'],
            'tc_num' => $pr['tc_num'],
            'tc_uom' => $pr['tc_uom'],
            'pr_id' => $pr['id'],
            'pr_code' => $pr['pr_code'],
            'pr_ln' => $pr['pr_ln'],
            'req_date' => $pr['req_date'],
            'sup_confirm_date' => $ioInfo['promise_date'],
            'price' => round($ioInfo['quote_price'], 2),
            'tax_price' => round($ioInfo['quote_price']*(1 + $taxRate), 2),
            'amount' => round($ioInfo['quote_price']*(1 /*+ $taxRate*/)*$pr['price_num'], 2),
            'tax_rate' => $taxRate,
            'status' => 'init'
        ];
        model('POItem', 'logic')->create($poItemData);
        return resultArray(2000);

    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe:根据询价 单下采购单
     */
    public function placePurchOrderFromIo($io){
        $now = time();
        /*$poData = [
            'pr_code' => $io['pr_code'],
            'sup_code' => $io['sup_code'],
            'is_include_tax' => 1,      //是否含税
            'status' => 'init',
            'create_at' => $now,
            'update_at' => $now,
        ];
        $poId = $this->insertGetId($poData);
        if(!$poId){
            return resultArray(5000);
        };*/
        $poItemData = [
            'po_id' => null,
            'item_code' => $io['item_code'],
            'item_name' => $io['item_name'],
            'sup_code' => $io['sup_code'],
            'sup_name' => $io['sup_name'],
            'price_num' => $io['price_num'],
            'price_uom' => $io['price_uom'],
            'tc_num' => $io['tc_num'],
            'tc_uom' => $io['tc_uom'],
            'pr_id' => $io['pr_id'],
            'pr_code' => $io['pr_code'],
            'pr_ln' => $io['pr_ln'],
            'sup_confirm_date' => $io['promise_date'],
            'req_date' => $io['req_date'],
            'price' => $io['quote_price'],
            'tax_price' => round($io['quote_price']*(1 + floatval($io['tax_rate'])), 2),
            'amount' => round($io['quote_price']*(1 /*+ floatval($io['tax_rate'])*/)*$io['price_num'], 2),
            'tax_rate' => $io['tax_rate'],
            'winbid_time' => $now
        ];
        model('POItem', 'logic')->isUpdate(false)->create($poItemData);

        return resultArray(2000);

    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 查询我的采购单
     * @param $loginUser
     */
    public function getMyPo($loginUser, $status = 'all', $pageParam = []){
        $orderCount = $this->countOrder($loginUser);
        $pageData = $this->getMyPoByStatus($loginUser, $status, $pageParam);
        $ret = array_merge(['orderCount' => $orderCount], $pageData);
        $code = empty($pageData['dataTotal']) ? 4004 : 2000;
        return resultArray($code, '', $ret);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 查询未读单数量
     * @param $user
     */
    public function countUnreadMsg($user){
        $cnt = $this->where('read_at', 'NULL')->where('sup_code', $user['sup_code'])->count();
        //dd($this->getLastSql());
        return $cnt;

    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 统计采购订单数量
     * @param $user
     *
     * @Success {Number} orderCount.unSign           待签合同单数.
     * @Success {Number} orderCount.unCheck          合同待审核单数.
     * @Success {Number} orderCount.unExecuting      待交货单数.
     * @Success {Number} orderCount.executing        部分交货单数.
     * @Success {Number} orderCount.exeAll           全部交货单数.
     */
    private function countOrder($user){
        $orderCount = [
            'unSign' => $this->countUnSignOrder($user),
            'unCheck' => $this->countUnCheckOrder($user),
            'unExecuting' => $this->countUnExeOrder($user),
            'executing' => $this->countExeOrder($user),
            'exeAll' => $this->countExeAllOrder($user),
        ];

        return $orderCount;
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 待签合同单数
     * @return int
     */
    private function countUnSignOrder($user){
        $cnt = $this->where('sup_code', $user['sup_code'])->where('status', 'in', 'sup_sure')->count();
        return $cnt;
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 合同待审核单数
     * @return int
     */
    private function countUnCheckOrder($user){
        $cnt = $this->where('sup_code', $user['sup_code'])->where('status', 'in', 'upload_contract')->count();
        return $cnt;
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 待交货单数
     * @return int
     */
    private function countUnExeOrder($user){
        $cnt = $this->alias('po')
            ->where('po.sup_code', $user['sup_code'])
            ->where('po.status', 'executing')
            ->join('atw_po_item pi', 'pi.po_id = po.id')
            ->where('pi.arv_goods_num', 0)
            ->count();
        //$this->getLastSql()
        return $cnt;
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 部分交货单数
     * @return int
     */
    private function countExeOrder($user){
        $cnt = $this->alias('po')
            ->where('po.sup_code', $user['sup_code'])
            ->where('po.status', 'executing')
            ->join('atw_po_item pi', 'pi.po_id = po.id')
            ->where('pi.arv_goods_num', '>', 0)
            ->where('pi.pro_goods_num', '>', 0)
            ->count();
        //$this->getLastSql()
        return $cnt;
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 全部交货单数
     * @return int
     */
    private function countExeAllOrder($user){
        $cnt = $this->alias('po')
            ->where('po.sup_code', $user['sup_code'])
            ->where('po.status', 'executing')
            ->join('atw_po_item pi', 'pi.po_id = po.id')
            ->where('pi.arv_goods_num', '>', 0)
            ->where('pi.pro_goods_num', '=', 0)
            ->count();
        //$this->getLastSql()
        return $cnt;
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 获取不同状态的 订单列表
     * @param $user
     * @param $status all=全部 unsign_contract=待签合同  upload_contract=合同待审核  executing_0=待交货 executing=部分交货 executing_all=全部交货 finish=结束
     * @param $pageParam
     *
     * @Success {Number} list.id             订单id.
     * @Success {String} list.code           订单号.
     * @Success {String} list.docDate        下单日期.
     * @Success {String} list.contractTime   合同签订日期.
     * @Success {String} list.status         状态 init=初始 sup_cancel=供应商取消 sup_edit=供应商修改 atw_sure=安特威确定 sup_sure = 供应商确定/待上传合同 upload_contract = 供应商已经上传合同contract_pass  = 合同审核通过 contract_refuse= 合同审核拒绝 executing  = 执行中 finish=结束
     * @Success {String} list.statusStr      状态 .
     * @Success {String} list.orderArvInfo   订单到货情况.
     * @Success {String} list.orderPayInfo   订单支付情况.
     * @Success {Array}  list.contractArr    合同映象 一位数组.
     * @Success {Number} page                页码.
     * @Success {Number} pageSize            每页数据量.
     * @Success {Number} dataTotal           数据总数.
     * @Success {Number} pageTotal           总页码数.
     */
    private function getMyPoByStatus($user, $status, $pageParam){

        $fields = [
            'po.id' => 'id', //
            'po.order_code' => 'code',
            'po.doc_date' => 'docDate',
            'po.contract_time' => 'contractTime',
            'po.status' => 'status',
            'po.contract' => 'contract',
            'pi.arv_goods_num' => 'arvNum',
            'pi.pro_goods_num' => 'proNum',
            'pi.amount' => 'amount',
            'pi.item_name' => 'itemName',
            'pi.item_code' => 'itemCode',
        ];

        $where = ['po.sup_code' => $user['sup_code']];
        if($status == 'unsign_contract'){
            $where['po.status'] = 'sup_sure';
        }elseif($status == 'upload_contract'){
            $where['po.status'] = 'upload_contract';
        }elseif($status == 'executing_0'){
            $where['po.status'] = 'executing';
            $where['pi.arv_goods_num'] = 0;
        }elseif($status == 'executing'){
            $where['po.status'] = 'executing';
            $where['pi.arv_goods_num'] = ['>', 0];
            $where['pi.pro_goods_num'] = ['>', 0];
        }elseif($status == 'executing_all'){
            $where['po.status'] = 'executing';
            $where['pi.arv_goods_num'] = ['>', 0];
            $where['pi.pro_goods_num'] = 0;
        }elseif($status == 'finish'){
            $where['po.status'] = 'finish';
        }

        $dataTotal = $this->alias('po')
            ->field($fields)
            ->join('atw_po_item pi', 'pi.po_id = po.id')
            ->where($where)
            ->group('po.id')
            ->count();
        $dbList = $this->alias('po')
            ->field($fields)
            ->join('atw_po_item pi', 'pi.po_id = po.id')
            ->where($where)
            ->group('po.id')
            ->page($pageParam['page'], $pageParam['pageSize'])
            ->order('po.create_at DESC')
            ->select();
        $readIds = [];
        foreach($dbList as &$item){
            $readIds[] = $item['id'];
            $item['docDate'] = empty($item['docDate']) ? '' : date('Y-m-d', $item['docDate']);
            $item['contractTime'] = empty($item['contractTime']) ? '' : date('Y-m-d', $item['contractTime']);
            $item['arvNum'] = intval($item['arvNum']);
            $item['proNum'] = intval($item['proNum']);
            $item['statusStr'] = self::STATUS_ARR[$item['status']];
            $item['contractArr'] = empty($item['contract']) ? [] : explode(',', $item['contract']);

            $item['orderArvInfo'] = $this->getArvInfo($item);
            $item['orderPayInfo'] = $this->getPayInfo($item);
        }

        $pageData = [
            'list' => $dbList,
            'page' => $pageParam['page'],
            'pageSize' => $pageParam['pageSize'],
            'dataTotal' => $dataTotal,
            'pageTotal' => floor($dataTotal/$pageParam['pageSize']) + 1,
        ];
        //更改阅读状态
        $this->isUpdate(true)->where('id', 'IN', $readIds)->update(['read_at' => time()]);
        return $pageData;
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe:
     * @param $po
     * @param $pi
     *
     * @success {String}  code           订单号.
     * @success {String}  docDate        下单日期.
     * @success {String}  contractTime   合同签订日期.
     * @success {String}  status         状态 init=初始 sup_cancel=供应商取消 sup_edit=供应商修改 atw_sure=安特威确定 sup_sure = 供应商确定/待上传合同 upload_contract = 供应商已经上传合同contract_pass  = 合同审核通过 contract_refuse= 合同审核拒绝 executing  = 执行中 finish=结束
     * @success {String}  statusStr      状态 .
     * @success {String}  orderArvInfo   订单到货情况.
     * @success {String}  orderPayInfo   订单支付情况.
     * @success {Array}   contractArr    合同映象 一位数组.
     * @success {Array}   itemArr        物料.
     * @success {Number}  itemArr.id             物料id.
     * @success {String}  itemArr.itemCode       物料编号.
     * @success {String}  itemArr.itemName       物料名称.
     * @success {Number}  itemArr.priceNum       计价数量.
     * @success {String}  itemArr.priceUom       计价单位.
     * @success {Number}  itemArr.price          单价（含税）.
     * @success {Number}  itemArr.taxPrice       含税单价(废弃).
     * @success {Number}  itemArr.subtotal       小计金额.
     * @success {Number}  itemArr.confirmDate    交期.
     */
    public function getDetailByPo($po){
        $itemArr = [];
        $pis = $this->getPiFromPo($po);
        foreach($pis as $pi){
            $itemArr[] = [
                'id' => $pi['id'],
                'itemCode' => $pi['item_code'],
                'itemName' => $pi['item_name'],
                'priceNum' => $pi['price_num'],
                'priceUom' => $pi['price_uom'],
                'price' => number_format($pi['price'], 2),
                'taxPrice' => number_format($pi['tax_price'], 2),
                'subtotal' => number_format($pi['price']*$pi['price_num'], 2),
                'confirmDate' => date('Y-m-d', date($pi['sup_confirm_date'])),
            ];
        }
        $retData = [
            'code' => $po['order_code'].'',
            'docDate' => empty($po['doc_date']) ? '未下单' : date('Y-m-d', $po['doc_date']),
            'contractTime' => empty($po['contract_time']) ? '未签合同' : date('Y-m-d', $po['contract_time']),
            'status' => $po['status'],
            'statusStr' => self::STATUS_ARR[$po['status']],
            'orderArvInfo' => $this->getArvInfo($po),
            'orderPayInfo' => $this->getPayInfo($po),
            'contractArr' => empty($po['contract']) ? [] : explode(',', $po['contract']),
            'itemArr' => $itemArr
        ];
        return resultArray(2000, '', $retData);

    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 查询po下的pi
     * @param $po
     */
    public function getPiFromPo($po){
        $pis = model('POItem', 'logic')->where('po_id', $po['id'])->select();
        if(empty($pis)){
            return [];
        }
        return collection($pis)->toArray();
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 查询订单下物料的到货进度
     * @param $po
     * @return string
     */
    public function getArvInfo($po){
        $pis = $this->getPiFromPo($po);
        $arvInfo = '';
        foreach($pis as $pi){
            $arvNum = intval($pi['arv_goods_num']);
            $proNum = intval($pi['pro_goods_num']);
            $arvInfo .= "$pi[item_name],已送货:{$arvNum} ，未送货:$proNum 。 \n";
        }
        return $arvInfo;
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe:  查询订单下物料的付款进度
     * @param $$po
     * @return string
     */
    public function getPayInfo($po){
        $pis = $this->getPiFromPo($po);
        $arvInfo = '';
        foreach($pis as $pi){
            $pi['amount'] = number_format($pi['amount'],2);
            $arvInfo .= "$pi[item_name],已付款:0.00元，未付款:$pi[amount]元。\n"; // FIXME
        }
        return $arvInfo;
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe:  供应商确定订单
     * @param $user
     * @param $po
     * @return string
     */
    public function agreePo($po){
        $this->updateStatus($po,'sup_sure');
        return resultArray(2000);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe:  供应商拒绝订单
     * @param $po
     * @return string
     */
    public function refusePo( $po){
        $this->updateStatus($po,'sup_cancel');
        $supLogic = model('SupplierInfo','logic');
        $sup = $supLogic->where('code',$po['sup_code'])->find();

        $sup->giveup_count++;
        $adjustScore = 15-5* $sup->readjust_count;
        $adjustScore = $adjustScore < 0?0:$adjustScore;
        $sup->credit_total = $sup->pass_rate*30+$sup->arv_rate*30+$adjustScore;
        $sup->save();
        return resultArray(2000);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 如果没就 就插入，如果没有就取出
     * @param $poData
     *
     *  $poData = [
     *       'pr_code' => $pr['pr_code'],
     *       'sup_code' => $ioInfo['sup_code'],
     *       'is_include_tax' => 1,      //是否含税
     *       'status' => 'init',
     *       'create_at' => $now,
     *       'update_at' => $now,
     * ];
     */
    private function insertOrGetId($poData){
        $findPo = $this->where('pr_code', $poData['pr_code'])->where('sup_code', $poData['sup_code'])->find();
        if(empty($findPo)){
            return $this->insertGetId($poData);
        }
        return $findPo['id'];
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 模拟u9 返回数据
     */
    private function mku9PoState($Num = 1){
        $list = [];
        for($i = 0; $i <= $Num; $i++){
            $list[] = [
                "AryQty" => "2.00",
                "DocLineNo" => "10",
                "DocNo" => "RE2016121056",
                "DocStatus" => "超额关闭",
                "ItemCode" => "140101000$i",
                "ItemName" => "APA-360x700-MT4-FC",
                "ItemSPE" => null,
                "NotAryQty" => "0.00",
                "SupCode" => null,
                "SupName" => null
            ];
        }
        return resultArray(2000, '', ['list' => $list]);
    }


}