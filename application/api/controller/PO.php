<?php
/**
 * 采购单
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/12
 * Time: 9:55
 */

namespace app\api\controller;

use think\Request;

class PO extends BaseController{


    /**
     * @api      {GET} /po 01.我的订单列表(ok)
     * @apiName  index
     * @apiGroup PO
     * @apiHeader {String} authorization-token           token.
     *
     * @apiParam {String} status=all      状态. all=全部 unsign_contract=待签合同  upload_contract=合同待审核  executing_0=待交货 executing=部分交货 executing_all=全部交货 finish=结束
     *
     * @apiSuccess {Object} orderCount                  .
     * @apiSuccess {Number} orderCount.unSign           待签合同单数.
     * @apiSuccess {Number} orderCount.unCheck          合同待审核单数.
     * @apiSuccess {Number} orderCount.unExecuting      待交货单数.
     * @apiSuccess {Number} orderCount.executing        部分交货单数.
     * @apiSuccess {Number} orderCount.exeAll           全部交货单数.
     * @apiSuccess {Array} list                 列表.
     * @apiSuccess {Number} list.id             订单id.
     * @apiSuccess {String} list.code           订单号.
     * @apiSuccess {String} list.docDate        下单日期.
     * @apiSuccess {String} list.contractTime   合同签订日期.
     * @apiSuccess {String} list.status         状态 init=初始 sup_cancel=供应商取消 sup_edit=供应商修改 atw_sure=安特威确定 sup_sure = 供应商确定/待上传合同 upload_contract = 供应商已经上传合同contract_pass  = 合同审核通过 contract_refuse= 合同审核拒绝 executing  = 执行中 finish=结束
     * @apiSuccess {String} list.statusStr      状态 .
     * @apiSuccess {String} list.orderArvInfo   订单到货情况.
     * @apiSuccess {String} list.orderPayInfo   订单支付情况.
     * @apiSuccess {Array} list.contractArr     合同映象 一位数组.
     */
    public function index(){
        $params = $this->getReqParams(['status' => 'all']);
        $rule = [
            'status' => 'in:all,unsign_contract,upload_contract,executing_0,executing,executing_all,finish'
        ];
        validateData($params, $rule);
        $pageParam = $this->getPagingParams();
        $loginRet = model('PO', 'logic')->getMyPo($this->loginUser, $params['status'], $pageParam);
        returnJson($loginRet);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(){
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request){
        //
    }

    /**
     * @api      {GET} /po/:id 04.我的订单详情(ok)
     * @apiName  read
     * @apiGroup PO
     * @apiHeader {String} authorization-token        token.
     *
     * @apiParam {Number} id               订单id.
     *
     * @apiSuccess {String}  code           订单号.
     * @apiSuccess {String}  docDate        下单日期.
     * @apiSuccess {String}  contractTime   合同签订日期.
     * @apiSuccess {String}  status         状态 init=初始 sup_cancel=供应商取消 sup_edit=供应商修改 atw_sure=安特威确定 sup_sure = 供应商确定/待上传合同 upload_contract = 供应商已经上传合同contract_pass  = 合同审核通过 contract_refuse= 合同审核拒绝 executing  = 执行中 finish=结束
     * @apiSuccess {String}  statusStr      状态 .
     * @apiSuccess {String}  orderArvInfo   订单到货情况.
     * @apiSuccess {String}  orderPayInfo   订单支付情况.
     * @apiSuccess {Array}   contractArr    合同映象 一位数组.
     * @apiSuccess {Array}   itemArr        物料.
     * @apiSuccess {Number}  itemArr.id             物料id.
     * @apiSuccess {String}  itemArr.itemCode       物料编号.
     * @apiSuccess {String}  itemArr.itemName       物料名称.
     * @apiSuccess {Number}  itemArr.priceNum       计价数量.
     * @apiSuccess {String}  itemArr.priceUom       计价单位.
     * @apiSuccess {Number}  itemArr.price          单价(含税).
     * @apiSuccess {Number}  itemArr.taxPrice       含税单价(废弃).
     * @apiSuccess {Number}  itemArr.subtotal       小计金额.
     * @apiSuccess {Number}  itemArr.confirmDate    交期.
     */
    public function read($id){
        $poLogic = model('PO', 'logic');
        $po = $poLogic->find($id);
        if(empty($po)){
            returnJson(4004, '无效的id='.$id);
        }
        returnJson($poLogic->getDetailByPo($po));
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id){
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request $request
     * @param  int            $id
     * @return \think\Response
     */
    public function update(Request $request, $id){
        //
    }

    /**
     * @api      {PUT} /po/agree 08.确定订单(ok)
     * @apiName  agree
     * @apiGroup PO
     * @apiHeader {String} authorization-token        token.
     *
     * @apiParam {Number} id               订单id.
     *
     */
    public function agree(){
        $id = input('id',0);
        $poLogic = model('PO','logic');
        $po = $poLogic->where('id',$id)
            ->where('sup_code',$this->loginUser['sup_code'])
            ->where('status','init')
            ->find($id);
        if(empty($po)){
            returnJson(4004,'',$poLogic->getLastSql());
        }
        returnJson($poLogic->agreePo($po));
    }

    /**
     * @api      {PUT} /po/refuse 09.拒绝订单(ok)
     * @apiName  refuse
     * @apiGroup PO
     * @apiHeader {String} authorization-token        token.
     *
     * @apiParam {Number} id               订单id.
     *
     */
    public function refuse(){
        $id = input('id',0);
        $poLogic = model('PO','logic');
        $po = $poLogic->where('id',$id)
            ->where('sup_code',$this->loginUser['sup_code'])
            ->where('status','init')
            ->find($id);
        if(empty($po)){
            returnJson(4004,'',$poLogic->getLastSql());
        }
        returnJson($poLogic->refusePo($po));
    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id){
        //
    }


}