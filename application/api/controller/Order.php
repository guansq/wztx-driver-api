<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/10
 * Time: 19:12
 */

namespace app\api\controller;

class Order extends BaseController {
    const TITLE = '您有新的司机报价';
    const SPTITLE = '您的订单已被接单';
    const SPCONTENT = '您的订单已被接单';

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
     * @apiParam   {String} type        订单状态（all全部状态，quote报价中，quoted已报价，待发货 distribute配送中（在配送-未拍照）发货中 photo 拍照完毕（订单已完成）） success 已完成的，包含未评论
     * @apiParam {Number} [page=1]                       页码.
     * @apiParam {Number} [pageSize=20]                  每页数据量.
     * @apiSuccess {Array}  list                         订单列表
     * @apiSuccess {String} list.order_id                订单ID
     * @apiSuccess {String} list.org_city               发货人省市区
     * @apiSuccess {String} list.dest_city              到达城市
     * @apiSuccess {String} list.org_address_name        出发地名称
     * @apiSuccess {String} list.dest_address_name       目的地名称
     * @apiSuccess {String} list.org_address_detail       发货人地址详情
     * @apiSuccess {String} list.dest_address_detail       目的地详情
     * @apiSuccess {String} list.weight                  货物重量
     * @apiSuccess {String} list.goods_name              货物名称
     * @apiSuccess {String} list.car_style_length        车长
     * @apiSuccess {String} list.car_style_type          车型
     * @apiSuccess {String} list.final_price             最终报价
     * @apiSuccess {String} list.system_price            系统价
     * @apiSuccess {String} list.mind_price              心理价位
     * @apiSuccess {String} list.usecar_time             用车时间
     * @apiSuccess {String} list.status init             初始状态（未分发订单前）quoted已报价-未配送（装货中）distribute配送中（在配送-未拍照）发货中 photo 拍照完毕（订单已完成）pay_failed（支付失败）/pay_success（支付成功）comment（已评论）
     * @apiSuccess {Number} page                         页码.
     * @apiSuccess {Number} pageSize                     每页数据量.
     * @apiSuccess {Number} dataTotal                    数据总数.
     * @apiSuccess {Number} pageTotal                    总页码数.
     *
     */
    public function listInfo() {
        $paramAll = $this->getReqParams([
            'type',
        ]);
        $rule = [
            'type' => ['require', '/^(all|quoted|distribute|photo|success)$/'],
        ];
        validateData($paramAll, $rule);
        $where = [];
        if ($paramAll['type'] != 'all') {
            if ($paramAll['type'] == 'success') {
                $where['status'] = ['in', ['pay_success', 'comment']];
            } else {
                $where['status'] = $paramAll['type'];
            }
        } else {
            $where['status'] = ['not in', ['quote']];
        }
        $where['dr_id'] = $this->loginUser['id'];
        $pageParam = $this->getPagingParams();
        $orderInfo = model('TransportOrder', 'logic')->getTransportOrderList($where, $pageParam);
        if (empty($orderInfo)) {
            returnJson('4004', '暂无订单信息');
        }
        $list = [];
        foreach ($orderInfo['list'] as $k => $v) {
            $list[$k]['order_id'] = $v['id'];
            $list[$k]['org_address_name'] = $v['org_address_name'];
            $list[$k]['dest_address_name'] = $v['dest_address_name'];
            $list[$k]['org_city'] = $v['org_city'];
            $list[$k]['dest_city'] = $v['dest_city'];
            $list[$k]['weight'] = strval($v['weight']);
            $list[$k]['goods_name'] = $v['goods_name'];
            $list[$k]['status'] = $v['status'];
            $list[$k]['car_style_length'] = $v['car_style_length'];
            $list[$k]['car_style_type'] = $v['car_style_type'];
            $list[$k]['final_price'] = wztxMoney($v['final_price']);
            $list[$k]['mind_price'] = wztxMoney($v['mind_price']);
            $list[$k]['system_price'] = wztxMoney($v['system_price']);
            $list[$k]['usecar_time'] = wztxDate($v['usecar_time']);
            $list[$k]['org_address_detail'] = $v['org_address_detail'];
            $list[$k]['dest_address_detail'] = $v['dest_address_detail'];
        }
        $orderInfo['list'] = $list;

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
     * @apiSuccess  {String} policy_code        保单编号
     * @apiSuccess  {Int} is_pay                是否支付1为已支付 0为未支付
     * @apiSuccess  {String} is_receipt         货物回单1-是-默认，2-否
     * @apiSuccess  {String} system_price       系统出价
     * @apiSuccess  {String} mind_price         货主出价
     * @apiSuccess  {String} final_price        总运费
     * @apiSuccess  {String} effective_time      在途时效
     * @apiSuccess  {String} remark              备注
     * @apiSuccess  {String} goods_id             货源ID
     */
    public function detail() {
        $paramAll = $this->getReqParams([
            'order_id',
        ]);
        $rule = [
            'order_id' => ['require', 'regex' => '^[0-9]*$'],
        ];

        validateData($paramAll, $rule);
        $orderInfo = model('TransportOrder', 'logic')->getTransportOrderQuoteInfo(['dr_id' => $this->loginUser['id'], 'id' => $paramAll['order_id']]);
        if (empty($orderInfo)) {
            returnJson('4004', '未获取到订单信息');
        }
        if ($this->loginUser['id'] == $orderInfo['dr_id']) {
            $drBaseInfo = model('DrBaseInfo', 'logic')->findInfoByUserId($this->loginUser['id']);
            $dr_phone = $drBaseInfo['phone'];
            $dr_real_name = $drBaseInfo['real_name'];
        } else {
            $dr_phone = '';
            $dr_real_name = '';
        }

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
            'policy_code' => $orderInfo['policy_code'],
            'is_pay' => $orderInfo['is_pay'],
            'is_receipt' => $orderInfo['is_receipt'],
            'system_price' => wztxMoney($orderInfo['system_price']),
            'mind_price' => wztxMoney($orderInfo['mind_price']),
            'final_price' => wztxMoney($orderInfo['final_price']),
            'effective_time' => $orderInfo['effective_time'],
            'remark' => $orderInfo['remark'],
            'goods_id' => $orderInfo['goods_id']
        ];
        if($orderInfo['status'] == 'quote'){
            $where = [
                'dr_id' => $orderInfo['dr_id'],
                'goods_id' => $orderInfo['goods_id']
            ];
            $detail['quote_price'] = getLastQuotePrice($where);
        }
        returnJson('2000', '成功', $detail);
    }

    /**
     * @api     {POST}  /order/uploadCerPic            上传到货凭证done
     * @apiName uploadCerPic
     * @apiGroup Order
     * @apiHeader {String} authorization-token           token.
     * @apiParam    {Int}    order_id           order_id
     * @apiParam    {String}    img_url         图片链接，多个用 | 分隔
     * @apiSuccess  {String} order_id         order_id
     * @apiParam  {String}  dest_address_maps            目的地址的坐标 如116.480881,39.989410
     */
    public function uploadCerPic() {
        $paramAll = $this->getReqParams([
            'order_id',
            'img_url',
            'dest_address_maps'
        ]);
        $rule = [
            'order_id' => ['require', 'regex' => '^[0-9]*$'],
            'img_url' => 'require',
            'dest_address_maps' => 'require|max:30',
        ];
        $address = explode(',', $paramAll['dest_address_maps']);
        $paramAll['dest_longitude'] = $address[0];
        $paramAll['dest_latitude'] = $address[1];
        validateData($paramAll, $rule);
        $orderInfo = model('TransportOrder', 'logic')->getTransportOrderInfo(['dr_id' => $this->loginUser['id'], 'id' => $paramAll['order_id'], 'status' => 'distribute']);//配送中的订单信息
        if (empty($orderInfo)) {
            returnJson(4004, '未获取到订单信息');
        }
        if ($orderInfo['status'] != 'distribute') {
            returnJson(4000, '当前状态不能拍照上传');
        }
        $data = [
            'status' => 'photo',
            'arr_cer_pic' => $paramAll['img_url'],
            'arr_time' => time(),
            'dest_address_maps' => $paramAll['dest_address_maps'],
            'dest_longitude' => $paramAll['dest_longitude'],
            'dest_latitude' => $paramAll['dest_latitude']
        ];
        //没有问题存入数据库
        $changeStatus = model('TransportOrder', 'logic')->updateTransport(['id' => $paramAll['order_id']], $data );
        if ($changeStatus['code'] != 2000) {
            returnJson($changeStatus);
        }
        returnJson(2000, '成功', ['order_id' => $paramAll['order_id']]);
    }

    /**
     * @api     {POST}  /order/shipping             司机进行发货动作done
     * @apiName shipping
     * @apiGroup Order
     * @apiHeader {String} authorization-token      token.
     * @apiParam  {Number}  order_id            order_id
     * @apiParam  {String}  org_address_maps            出发地地址的坐标 如116.480881,39.989410
     */
    public function shipping() {
        $paramAll = $this->getReqParams([
            'order_id',
            'org_address_maps'
        ]);
        $rule = [
            'order_id' => ['require', 'regex' => '^[0-9]*$'],
            'org_address_maps' => 'require|max:30',
        ];
        validateData($paramAll, $rule);
        $where = [
            'dr_id' => $this->loginUser['id'],
            'id' => $paramAll['order_id'],
            'status' => 'quoted',//已确认订单状态
        ];
        $address = explode(',', $paramAll['org_address_maps']);
        $paramAll['org_longitude'] = $address[0];
        $paramAll['org_latitude'] = $address[1];
        $data = [
            'status' => 'distribute',//更改为配送中的状态
            'send_time' => time(),
            'org_address_maps'=>$paramAll['org_address_maps'],
            'org_longitude'=>  $paramAll['org_longitude'],
            'org_latitude'=>  $paramAll['org_latitude'],

        ];
        //通过order_id得到sp_id
        $spId = getSpIdByOrderId($paramAll['order_id']);
        if (empty($spId)) {
            returnJson(4000, '发货失败');
        }
        $ret = model('TransportOrder', 'logic')->updateTransport($where, $data);
        if ($ret['code'] == 4000) {
            returnJson(4000, '发货失败');
        }
        //发送推送信息给货主
        $push_token = getSpPushToken($spId);
        if (!empty($push_token)) {
            pushInfo($push_token, '您的订单已经在配送中啦', $rt_key = 'wztx_shipper');//推送给货主端
        }
        returnJson(2000, '发货成功');
    }

}