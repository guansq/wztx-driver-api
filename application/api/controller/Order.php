<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/10
 * Time: 19:12
 */

namespace app\api\controller;

class Order extends BaseController {
    /*
     * @api     {POST}  /order/showQuoteInfo        显示分配中的报价信息
     * @apiName showQuoteInfo
     * @apiGroup Order
     * @apiHeader {String} authorization-token           token.
     * @apiParam  {String}  quote_id        报价ID
     * @apiSuccess  {String} org_address_name   起始地
     * @apiSuccess  {String} dest_address_name  目的地
     * @apiSuccess  {String} org_send_name      寄件人姓名
     * @apiSuccess  {String} org_phone          寄件人电话
     * @apiSuccess  {String} org_address        寄件人地址
     * @apiSuccess  {String} goods_name         货品名称
     * @apiSuccess  {String} weight             重量
     * @apiSuccess  {Float}  system_price       系统价 保留2位小数点
     * @apiSuccess  {Float}  mind_price         货主出价 保留2位小数点
     */
    public function showQuoteInfo() {

    }

    /**
     * @api     {POST}  /order/listInfo             订单列表done
     * @apiName listInfo
     * @apiGroup Order
     * @apiHeader {String} authorization-token           token.
     * @apiParam   {String} type        订单状态（all全部状态，quote报价中，quoted已报价，待发货 distribute配送中（在配送-未拍照）发货中 photo 拍照完毕（订单已完成））
     * @apiParam {Number} [page=1]                  页码.
     * @apiParam {Number} [pageSize=20]             每页数据量.
     * @apiSuccess {Array}  list        订单列表
     * @apiSuccess {String} list.org_address_name        出发地名称
     * @apiSuccess {String} list.dest_address_name       目的地名称
     * @apiSuccess {String} list.weight                  货物重量
     * @apiSuccess {String} list.goods_name              货物名称
     * @apiSuccess {String} list.car_style_length        车长
     * @apiSuccess {String} list.car_style_type          车型
     * @apiSuccess {String} list.final_price             出价
     * @apiSuccess {String} list.usecar_time             用车时间
     * @apiSuccess {Number} page                页码.
     * @apiSuccess {Number} pageSize            每页数据量.
     * @apiSuccess {Number} dataTotal           数据总数.
     * @apiSuccess {Number} pageTotal           总页码数.
     * @apiSuccess {String} list.status init 初始状态（未分发订单前）quote报价中（分发订单后）quoted已报价-未配送（装货中）distribute配送中（在配送-未拍照）发货中 photo 拍照完毕（订单已完成）pay_failed（支付失败）/pay_success（支付成功）comment（已评论）
     *
     */
    public function listInfo() {
        $paramAll = $this->getReqParams([
            'type',
        ]);
        $rule = [
            'type' => ['require', '/^(all|quote|quoted|distribute|photo)$/'],
        ];
        validateData($paramAll, $rule);
        $where = [];
        if ($paramAll['type'] != 'all') {
            $where['status'] = $paramAll['type'];
        }
        $where['dr_id'] = $this->loginUser['id'];
        $pageParam = $this->getPagingParams();
        $orderInfo = model('TransportOrder', 'logic')->getTransportOrderList($where, $pageParam);
        if (empty($orderInfo)) {
            returnJson('4000', '暂无订单信息');
        }
        returnJson('2000', '成功', $orderInfo);
    }

    /**
     * @api     {POST}  /order/detail            订单详情done
     * @apiName detail
     * @apiGroup Order
     * @apiHeader {String} authorization-token           token.
     * @apiParam    {Int}    order_id           订单ID
     * @apiSuccess  {String} status             init 初始状态（未分发订单前）quote报价中（分发订单后）quoted已报价-未配送（装货中）distribute配送中（在配送-未拍照）发货中 photo 拍照完毕（订单已完成）pay_failed（支付失败）/pay_success（支付成功）comment（已评论）
     * @apiSuccess  {String} order_code         订单号
     * @apiSuccess  {String} goods_name         货品名称
     * @apiSuccess  {String} weight             重量
     * @apiSuccess  {String} org_city           起始地
     * @apiSuccess  {String} dest_city          目的地
     * @apiSuccess  {String} dest_receive_name  收货人姓名
     * @apiSuccess  {String} dest_phone         收货人电话
     * @apiSuccess  {String} dest_address_name  收货人地址
     * @apiSuccess  {String} dest_address_detail收货人地址详情
     * @apiSuccess  {String} org_send_name      寄件人姓名
     * @apiSuccess  {String} org_phone          寄件人电话
     * @apiSuccess  {String} org_address_name   寄件人地址
     * @apiSuccess  {String} org_address_datail 寄件人地址详情
     * @apiSuccess  {String} usecar_time        用车时间
     * @apiSuccess  {String} send_time          发货时间
     * @apiSuccess  {String} arr_time           到达时间
     * @apiSuccess  {String} real_name          车主姓名
     * @apiSuccess  {String} phone              联系电话
     * @apiSuccess  {String} is_receipt         货物回单1-是-默认，2-否
     * @apiSuccess  {String} system_price       系统出价
     * @apiSuccess  {String} mind_price         货主出价
     * @apiSuccess  {String} final_price        总运费
     */
    public function detail() {
        $paramAll = $this->getReqParams([
            'order_id',
        ]);
        $rule = [
            'order_id' => ['require', 'regex' => '\d'],
        ];

        validateData($paramAll, $rule);
        $orderInfo = model('TransportOrder', 'logic')->getTransportOrderInfo(['dr_id' => $this->loginUser['id'], 'id' => $paramAll['order_id']]);
        if (empty($orderInfo)) {
            returnJson('4000', '未获取到订单信息');
        }
        $drBaseInfo = model('DrBaseInfo', 'logic')->findInfoByUserId($this->loginUser['id']);
        $dr_phone = $drBaseInfo['phone'];
        $dr_real_name = $drBaseInfo['real_name'];
        $detail = [
            'status' => $orderInfo['status'],
            'order_code' => $orderInfo['order_code'],
            'goods_name' => $orderInfo['goods_name'],
            'weight' => strval($orderInfo['weight']),
            'org_city' => $orderInfo['org_city'],
            'dest_city' => $orderInfo['dest_city'],
            'dest_receive_name' => $orderInfo['dest_receive_name'],
            'dest_phone' => $orderInfo['dest_phone'],
            'dest_address_name' => $orderInfo['dest_address_name'],
            'dest_address_detail' => $orderInfo['dest_address_detail'],
            'org_send_name' => $orderInfo['org_send_name'],
            'org_phone' => $orderInfo['org_phone'],
            'org_address_name' => $orderInfo['org_address_name'],
            'org_address_detail' => $orderInfo['org_address_detail'],
            'usecar_time' => wztxDate($orderInfo['usecar_time']),
            'send_time' => wztxDate($orderInfo['send_time']),
            'arr_time' => wztxDate($orderInfo['arr_time']),
            'real_name' => $dr_real_name,
            'phone' => $dr_phone,
            'is_receipt' => $orderInfo['is_receipt'],
            'system_price' => wztxMoney($orderInfo['system_price']),
            'mind_price' => wztxMoney($orderInfo['mind_price']),
            'final_price' => wztxMoney($orderInfo['final_price']),
        ];
        returnJson('2000', '成功', $detail);
    }
}