<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/7
 * Time: 9:59
 */
namespace app\api\controller;

class Pay extends BaseController{
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
     * @apiSuccess {String}  uninvoicing_singular_total_order        累计未结账单数
     * @apiSuccess {String}  uninvoicing_singular_total_money        累计未结金额数
     * @apiSuccess {String}  withdrawal_money        可提现金额
     * @apiSuccess {String}  bonus                   我的推荐奖励
     */
    public function index(){

    }

    /**
     * @api {POST} /pay/withDraw  提现
     * @apiName withDraw
     * @apiGroup Pay
     * @apiHeader {String} authorization-token      token.
     * @apiParam  {String} withdrawal_amount        提现金额
     * @apiParam  {String} bank_name        银行名称
     * @apiParam  {String} payment_account        收款账号
     * @apiParam  {String} account_name        开户名
     *
     */
    public function withDraw(){

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
    public function recharge(){

    }

    /**
     * @api {POST}  /pay/showPayRecord 查看账单
     * @apiName showPayRecord
     * @apiGroup Pay
     * @apiHeader {String} authorization-token          token.
     * @apiSuccess {Array}   list                       账单列表
     * @apiSuccess {String}   list.order_id             订单ID
     * @apiSuccess {Number}   list.is_pay               1为已支付   0为未支付
     * @apiSuccess {String}   list.send_name            发货人姓名
     * @apiSuccess {String}   list.org_address_name     发货地址
     * @apiSuccess {String}   list.final_price          运价
     * @apiSuccess {String}   list.pay_time             订单完成时间
     */
    public function showPayRecord(){

    }

    /**
     * @api {POST}  /pay/showCashRecord 提现记录
     * @apiName showCashRecord
     * @apiGroup Pay
     * @apiHeader {String} authorization-token          token.
     * @apiSuccess {Array}   list                       提现记录
     */
    public function showCashRecord(){

    }
}