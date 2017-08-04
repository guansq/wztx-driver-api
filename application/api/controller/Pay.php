<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/7
 * Time: 9:59
 */

namespace app\api\controller;

use  think\Db;

class Pay extends BaseController {
    /**
     * @api {GET} /pay 我的钱包
     * @apiName index
     * @apiGroup Pay
     * @apiHeader {String} authorization-token      token.
     * @apiSuccess {String} balance         账户余额
     * @apiSuccess {Int}    pre_month_total_order   上月累计单数
     * @apiSuccess {String}  pre_month_total_money   上月累计金额
     * @apiSuccess {Int}    cur_month_total_order   本月累计单数
     * @apiSuccess {String}  month_total_money        本月累计金额
     * @apiSuccess {String}  year_total_money        年累计金额
     * @apiSuccess {Int}  uninvoicing_singular_total_order        累计未结账单数
     * @apiSuccess {String}  uninvoicing_singular_total_money        累计未结金额数
     * @apiSuccess {String}  withdrawal_money        可提现金额和余额一致
     * @apiSuccess {String}  bonus                   我的推荐奖励
     */
    public function index() {
        $begin_time = time();
        $end_time = strtotime("-1 year");
        $last_month_time = date('Y-m', strtotime("-1 month"));
        $now_month_time = date('Y-m');
        $where['pay_time'] = array('between', array($end_time, $begin_time));
        $where['status'] = ['in', ['pay_success', 'comment']];
        $where['dr_id'] = $this->loginUser['id'];
        $result = model('TransportOrder', 'logic')->getSuccessListInfo($where);
        $pre_month_total_order = 0;
        $pre_month_total_money = 0;
        $cur_month_total_order = 0;
        $month_total_money = 0;
        $year_total_money = 0;
        foreach ($result as $k => $v) {
            if ($v['month'] == $now_month_time) {
                $cur_month_total_order = $v['order_amount'];
                $month_total_money = $v['tran_total'];
            }
            if ($v['month'] == $last_month_time) {
                $pre_month_total_order = $v['order_amount'];
                $pre_month_total_money = $v['tran_total'];
            }
            $year_total_money = $year_total_money + $v['tran_total'];
        }
        $resultunb = model('TransportOrder', 'logic')->getUnbalanced(['is_clear' => 0, 'dr_id' => $this->loginUser['id']]);

        $uninvoicing_singular_total_order = empty($resultunb[0]['order_amount']) ? 0 : $resultunb[0]['order_amount'];
        $uninvoicing_singular_total_money = empty($resultunb[0]['tran_total']) ? 0 : $resultunb[0]['tran_total'];
        $drBaseInfoLogic = model('DrBaseInfo', 'logic');
        $baseUserInfo = $drBaseInfoLogic->findInfoByUserId($this->loginUser['id']);
        $balance = $withdrawal_money = empty($baseUserInfo['cash']) ? '0' : $baseUserInfo['cash'];
        $bonus = model('DrBaseInfo', 'logic')->getRecommBonusAll(['share_id' => $this->loginUser['id'], 'status' => 0, 'type' => 1]);
        $bonus = empty($bonus[0]['amount'])?0:$bonus[0]['amount'];
        $return_msg = [
            'balance' =>wztxMoney($balance) ,
            'pre_month_total_order' => $pre_month_total_order,
            'pre_month_total_money' => wztxMoney($pre_month_total_money),
            'cur_month_total_order' => $cur_month_total_order,
            'month_total_money' =>wztxMoney( $month_total_money),
            'year_total_money' => wztxMoney($year_total_money),
            'uninvoicing_singular_total_order' => $uninvoicing_singular_total_order,
            'uninvoicing_singular_total_money' => wztxMoney($uninvoicing_singular_total_money),
            'withdrawal_money' => wztxMoney($withdrawal_money),
            'bonus' => wztxMoney($bonus),
        ];
        returnJson(2000,'成功',$return_msg);
    }

    /**
     * @api {POST} /pay/withDraw  提现done
     * @apiName withDraw
     * @apiGroup Pay
     * @apiHeader {String} authorization-token      token.
     * @apiParam  {String} withdrawal_amount        提现金额
     * @apiParam  {String} bank_name        银行名称
     * @apiParam  {String} payment_account        收款账号
     * @apiParam  {String} account_name        开户名
     *
     */
    public function withDraw() {

        $paramAll = $this->getReqParams([
            'withdrawal_amount',
            'bank_name',
            'payment_account',
            'account_name',
        ]);
        $rule = [
            'withdrawal_amount' => ['require', '^[0-9]+(.[0-9]{2})?$'],
            'bank_name' => 'require',
            'payment_account' => 'require',
            'account_name' => 'require',
        ];
        validateData($paramAll, $rule);
        $drBaseInfoLogic = model('DrBaseInfo', 'logic');
        if (empty(floatval(wztxMoney($paramAll['withdrawal_amount'])))) {
            returnJson(4000, '提现金额不能为0');
        }
        $baseUserInfo = $drBaseInfoLogic->findInfoByUserId($this->loginUser['id']);
        if (empty($baseUserInfo)) {
            returnJson(4000, '未找到用户信息');
        }
        if ($baseUserInfo['auth_status'] != 'pass') {
            returnJson(4000, '当前认证状态不支持提现');
        }
        if ($baseUserInfo['bond_status'] == 'frozen') {
            returnJson(4000, '冻结账户不支持提现');
        }
        if (wztxMoney($baseUserInfo['cash']) < wztxMoney($paramAll['withdrawal_amount'])) {
            returnJson(4000, '提现金额大于可提现金额');
        }
        //当前有提现订单不能申请提现
        $where['status'] = ['in', ['init']];
        $where['base_id'] = $this->loginUser['id'];
        $ret = model('WithDraw', 'logic')->getWithDrawListIn($where);
        if (!empty($ret)) {
            returnJson(4000, '当前有提现订单存在，不能提现');
        }
        //完善个人信息填写  sp_id
        $paramAll['base_id'] = $this->loginUser['id'];
        $paramAll['real_name'] = $baseUserInfo['real_name'];
        $paramAll['phone'] = $baseUserInfo['phone'];
        $paramAll['withdraw_code'] = order_num();
        $paramAll['type'] = 'driver';
        $paramAll['status'] = 'init';
        $paramAll['amount'] = wztxMoney($paramAll['withdrawal_amount']);
        $paramAll['real_amount'] = wztxMoney($paramAll['withdrawal_amount']);
        //  $paramAll['deposit_name'] =  $baseUserInfo['amount'];
        $paramAll['bank'] = $paramAll['bank_name'];
        $paramAll['account'] = $paramAll['payment_account'];
        $paramAll['bank_person_name'] = $paramAll['account_name'];
        $paramAll['balance'] = wztxMoney($baseUserInfo['cash']) - wztxMoney($paramAll['withdrawal_amount']);

        //没有问题存入数据库 更新用户可提现金额
        // 启动事务
        Db::startTrans();
        try {
            $ret = model('WithDraw', 'logic')->saveWithDraw($paramAll);
            if ($ret["code"] == 2000) {
                $changeStatus = model('DrBaseInfo', 'logic')->updateBaseUserInfo(['id' => $paramAll['base_id']], ['cash' => $paramAll['balance'], 'update_at' => time()]);
                if ($changeStatus["code"] == 2000) {// 提交事务
                    Db::commit();
                    returnJson($changeStatus);
                }
            } else {
                returnJson(4000, '提交提现失败，稍后重试');
            }

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            returnJson(4000, '提交提现失败，稍后重试');
        }
    }

    /**
     * @api {POST} /pay/recharge  充值
     * @apiName recharge
     * @apiGroup Pay
     * @apiHeader {String} authorization-token      token.
     * @apiParam  {String}  real_amount              充值金额
     * @apiParam  {Int}    pay_way                  支付方式 1=支付宝，2=微信
     * @apiSuccess {Int}   pay_status               支付状态 0=未支付，1=支付成功，2=支付失败
     * @apiSuccess {Int}   balance                  充值之前的金额
     * @apiSuccess {Array} pay_info                 支付返回信息
     */
    public function recharge() {

    }

    /**
     * @api {POST}  /pay/showPayRecord 查看账单done
     * @apiName showPayRecord
     * @apiGroup Pay
     * @apiHeader {String} authorization-token          token.
     * @apiParam {Number} [page=1]                      页码.
     * @apiParam {Number} [pageSize=20]                 每页数据量.
     * @apiParam    {Int}    is_pay                     是否支付1为已支付 0为未支付
     * @apiSuccess {Array}   list                       账单列表
     * @apiSuccess {String}   list.order_id             订单ID
     * @apiSuccess {String}   list.real_name            货主姓名
     * @apiSuccess {String}   list.company_name         货主公司名称
     * @apiSuccess {String}   list.customer_type        货主类型
     * @apiSuccess {String}   list.org_city             发货地址
     * @apiSuccess {String}   list.dest_city            收货地址
     * @apiSuccess {String}   list.final_price          运价
     * @apiSuccess {String}   list.pay_time             订单完成时间
     * @apiSuccess {String}   list.usecar_time          用车时间
     * @apiSuccess  {Int}     list.is_pay               是否支付1为已支付 0为未支付
     * @apiSuccess  {String}  list.status               photo 拍照完毕（订单已完成） sucess(完成后的所有状态)pay_failed（支付失败）/pay_success（支付成功）comment（已评论）
     * @apiSuccess {Number} page                        页码.
     * @apiSuccess {Number} pageSize                    每页数据量.
     * @apiSuccess {Number} dataTotal                   数据总数.
     * @apiSuccess {Number} pageTotal                   总页码数.
     */
    public function showPayRecord() {
        $paramAll = $this->getReqParams([
            'is_pay',
        ]);
        $rule = [
            'is_pay' => ['require', '/^(0|1)$/'],
        ];

        validateData($paramAll, $rule);
        $where['status'] = ['in', ['photo', 'pay_failed', 'pay_success', 'comment']];
        $where['is_pay'] = $paramAll['is_pay'];
        $where['dr_id'] = $this->loginUser['id'];
        $pageParam = $this->getPagingParams();
        $orderInfo = model('TransportOrder', 'logic')->getTransportOrderList($where, $pageParam);
        if (empty($orderInfo)) {
            returnJson('4004', '暂无订单信息');
        }
        $list = [];
        foreach ($orderInfo['list'] as $k => $v) {
            $list[$k]['order_id'] = $v['id'];
            $list[$k]['org_city'] = $v['org_city'];
            $list[$k]['dest_city'] = $v['dest_city'];
            if (!empty($v['sp_id'])) {
                $baseUserInfo = getBaseSpUserInfo($v['sp_id']);
                $list[$k]['real_name'] = $baseUserInfo['real_name'];
                $list[$k]['company_name'] = getCompanyName($baseUserInfo);
                $list[$k]['customer_type'] = $baseUserInfo['type'];
            } else {
                $list[$k]['real_name'] = '';
                $list[$k]['company_name'] = '';
                $list[$k]['customer_type'] = '';
            }
            $list[$k]['final_price'] = wztxMoney($v['final_price']);
            $list[$k]['pay_time'] = wztxDate($v['pay_time']);
            $list[$k]['usecar_time'] = wztxDate($v['usecar_time']);
            $list[$k]['is_pay'] = $v['is_pay'];
            $list[$k]['status'] = $v['status'];
        }
        $orderInfo['list'] = $list;
        returnJson('2000', '成功', $orderInfo);
    }

    /**
     * @api {POST}  /pay/showCashRecord 提现记录done
     * @apiName showCashRecord
     * @apiGroup Pay
     * @apiHeader {String} authorization-token          token.
     * @apiParam {Number} [page=1]                       页码.
     * @apiParam {Number} [pageSize=20]                  每页数据量.
     * @apiSuccess {Array}   list                       提现记录
     * @apiSuccess {String}   list.withdrawal_amount    提现金额
     * @apiSuccess {String}   list.bank_name            银行名称
     * @apiSuccess {String}   list.payment_account      收款账号
     * @apiSuccess {String}   list.create_at            提交提现时间
     * @apiSuccess {String}   list.status               提现状态init=未处理，agree=后台同意，refuse=已拒绝，pay_success=银行返回成功，pay_fail=银行返回失败
     * @apiSuccess {Number} page                         页码.
     * @apiSuccess {Number} pageSize                     每页数据量.
     * @apiSuccess {Number} dataTotal                    数据总数.
     * @apiSuccess {Number} pageTotal                    总页码数.
     */
    public function showCashRecord() {
        $pageParam = $this->getPagingParams();
        //$where['status'] = ['in',['pay_success']];
        $where['base_id'] = $this->loginUser['id'];
        $ret = model('WithDraw', 'logic')->getWithDrawList($where, $pageParam);
        if (empty($ret)) {
            returnJson(4004, '未获取到提现记录');
        }
        $list = [];
        foreach ($ret['list'] as $k => $v) {
            $v['account'] = substr_replace($v['account'], "******", -10, 6);
            $list[$k]['id'] = $v['id'];
            $list[$k]['withdrawal_amount'] = wztxMoney($v['real_amount']);
            $list[$k]['bank_name'] = $v['bank'];
            $list[$k]['account'] = $v['account'];
            $list[$k]['create_at'] = wztxDate($v['create_at']);
            $list[$k]['status'] = $v['status'];
        }
        $ret['list'] = $list;
        returnJson(2000, '成功', $ret);
    }
}