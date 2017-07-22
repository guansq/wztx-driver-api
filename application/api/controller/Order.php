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
            'type' => ['require', '/^(all|quote|quoted|distribute|photo|success)$/'],
        ];
        validateData($paramAll, $rule);
        $where = [];
        if ($paramAll['type'] != 'all') {
            if($paramAll['type'] == 'success'){
                $where['status'] = ['in',['pay_success','comment']];
            }else{
                $where['status'] = $paramAll['type'];
            }
        }
        $where['dr_id'] = $this->loginUser['id'];
        $pageParam = $this->getPagingParams();
        $orderInfo = model('TransportOrder', 'logic')->getTransportOrderList($where, $pageParam);
        if (empty($orderInfo)) {
            returnJson('4004', '暂无订单信息');
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
     * @apiSuccess  {String} policy_code        保单编号
     * @apiSuccess  {Int} is_pay                是否支付1为已支付 0为未支付
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
            'order_id' => ['require', 'regex' => '^[0-9]*$'],
        ];

        validateData($paramAll, $rule);
        $orderInfo = model('TransportOrder', 'logic')->getTransportOrderQuoteInfo(['b.dr_id' => $this->loginUser['id'], 'a.id' => $paramAll['order_id']]);
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
        ];
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
     */
    public function uploadCerPic() {
        $paramAll = $this->getReqParams([
            'order_id',
            'img_url',
        ]);
        $rule = [
            'order_id' => ['require', 'regex' => '^[0-9]*$'],
            'img_url' => 'require',
        ];

        validateData($paramAll, $rule);
        $orderInfo = model('TransportOrder', 'logic')->getTransportOrderInfo(['dr_id' => $this->loginUser['id'], 'id' => $paramAll['order_id'],'status' => 'distribute']);//配送中的订单信息
        if (empty($orderInfo)) {
            returnJson(4004, '未获取到订单信息');
        }
        if($orderInfo['status'] != 'distribute'){
            returnJson(4000, '当前状态不能拍照上传');
        }
        //没有问题存入数据库
        $changeStatus = model('TransportOrder', 'logic')->updateTransport(['id' => $paramAll['order_id']], ['status' => 'photo','arr_cer_pic'=>$paramAll['img_url'],'arr_time'=>time()]);
        if ($changeStatus['code'] != 2000) {
            returnJson($changeStatus);
        }
        returnJson(2000, '成功',['order_id'=>$paramAll['order_id']]);
    }

    /**
     * @api     {POST}  /order/shipping             司机进行发货动作done
     * @apiName shipping
     * @apiGroup Order
     * @apiHeader {String} authorization-token      token.
     * @apiParam  {Number}  order_id            order_id
     */
    public function shipping(){
        $paramAll = $this->getReqParams(['order_id']);
        $rule = ['order_id' => ['require', 'regex' => '^[0-9]*$']];
        validateData($paramAll, $rule);
        $where = [
            'dr_id' => $this->loginUser['id'],
            'id' => $paramAll['order_id'],
            'status' => 'quoted',//已确认订单状态
        ];
        $data = [
            'status' => 'distribute',//更改为配送中的状态
            'send_time' => time()
        ];
        //通过order_id得到sp_id
        $spId = getSpIdByOrderId($paramAll['order_id']);
        if(empty($spId)){
            returnJson(4000,'发货失败');
        }
        $ret = model('TransportOrder', 'logic')->updateTransport($where,$data);
        if($ret['code'] == 4000){
            returnJson(4000,'发货失败');
        }
        //发送推送信息给货主
        $push_token = getSpPushToken($spId);
        if(!empty($push_token)){
            pushInfo($push_token,'您的订单已经在配送中啦',$rt_key='wztx_shipper');//推送给货主端
        }
        returnJson(2000,'发货成功');
    }

    /**
     * @api     {GET}       /order/goodsList        货源列表（根据设定路线展示）done
     * @apiName goodsList
     * @apiGroup Order
     * @apiHeader {String} authorization-token      token.
     * @apiParam   {String} [org_city]        出发地
     * @apiParam   {String} [dest_city]     目的地
     * @apiParam   {Number} [car_style_length_id]     车长ID
     * @apiParam   {Number} [car_style_type_id]     车型ID
     * @apiSuccess  {Array} list            列表
     * @apiSuccess  {String} list.order_id       订单ID
     * @apiSuccess  {String} list.org_city       出发地
     * @apiSuccess  {String} list.dest_city      目的地
     * @apiSuccess  {String} list.mind_price     心理价格
     * @apiSuccess  {String} list.system_price   系统价格
     * @apiSuccess  {String} list.goods_name     货物名称
     * @apiSuccess  {String} list.weight         总重量（吨）
     */
    public function goodsList(){
        $paramAll = $this->getReqParams(['org_city','dest_city','car_style_length_id','car_style_type_id']);
        $pageParam =$this->getPagingParams();
        $where = [];
        if(isset($paramAll['org_city'])&& !empty($paramAll['org_city'])){
            $where['org_city'] = ['like',"%{$paramAll['org_city']}%"];
        }
        if(isset($paramAll['dest_city'])&& !empty($paramAll['dest_city'])){
            $where['dest_city'] = ['like',"%{$paramAll['dest_city']}%"];
        }
        if(isset($paramAll['car_style_length_id'])&& !empty($paramAll['car_style_length_id'])){
            $where['car_style_length_id'] = ['like',"%{$paramAll['car_style_length_id']}%"];
        }
        if(isset($paramAll['car_style_type_id'])&& !empty($paramAll['car_style_type_id'])){
            $where['car_style_type_id'] = ['like',"%{$paramAll['car_style_type_id']}%"];
        }
        $where['status'] = 'quote';//待报价
        $ret = model('TransportOrder','logic')->getTransportOrderList($where,$pageParam);
        returnJson('2000', '成功', $ret);
    }

    /**
     * @api {POST}  /order/saveQuote        提交货源报价done
     * @apiName saveQuote
     * @apiGroup    Order
     * @apiHeader   {String}    authorization-token     token.
     * @apiParam    {String}     order_id                订单ID。
     * @apiParam    {String}     dr_price                司机出价
     * @apiParam    {String}     is_receive          是否立即下单 0表示不立即下单 1表示立即下单
     */
    public function saveQuote(){
        //判断订单ID是否合法通过订单ID生成已报价的报价单（如果立即下单，更改订单，价格状态）->判断该订单下面的询价单是否有报价过，无报价发送短信->
        $paramAll = $this->getReqParams(['order_id','dr_price','is_receive']);
        $rule = ['order_id' => 'require','dr_price' => 'require','is_receive'=>'require'];
        validateData($paramAll,$rule);
        $orderInfo = model('TransportOrder','logic')->getTransportOrderInfo(['status'=>'quote','id'=>'order_id']);//待报价订单
        if(empty($orderInfo)){
            returnJson(4000,'没有该订单或该订单已被接单');
        }
        //查询是否是第一次报价
        $quote_time = model('Quote','logic')->findOneQuote(['order_id'=>$paramAll['order_id'],'status'=>'quote']);//该订单的报价次数
        $info['goods_name'] = $orderInfo['goods_name'];
        $info['weight'] = $orderInfo['weight'];
        $info['order_id'] = $orderInfo['id'];
        $info['dr_id'] = $this->loginUser['id'];
        $info['sp_id'] = $orderInfo['sp_id'];
        $info['system_price'] = $orderInfo['system_price'];
        $info['sp_price'] = $orderInfo['mind_price'];
        $info['dr_price'] = $paramAll['dr_price'];
        $info['is_receive'] = $paramAll['is_receive'];
        $info['status'] = 'quote';
        $info['usecar_time'] = $orderInfo['usecar_time'];
        $info['car_style_length'] = $orderInfo['car_style_length'];
        $info['car_style_type'] = $orderInfo['car_style_type'];
        $info['org_city'] = $orderInfo['org_city'];
        $info['dest_city'] = $orderInfo['dest_city'];
        $info['org_address_name'] = $orderInfo['org_address_name'];
        $info['dest_address_name'] = $orderInfo['dest_address_name'];
        $info['org_address_detail'] = $orderInfo['org_address_detail'];
        $info['dest_address_detail'] = $orderInfo['dest_address_detail'];
        $result = model('Quote','logic')->saveQuote($info);//生成报价单

        if($paramAll['is_receive'] == 1){
            //更改订单状态
            $data = [
                'status' => 'quoted',
                'final_price' => $paramAll['dr_price'],
                'dr_id' => $this->loginUser['id'],
            ];
            //更改订单状态为已被接单状态
            $result = model('TransportOrder','logic')->updateTransport(['id'=>$paramAll['order_id'],'sp_id'=>$info['sp_id'],'status'=>'quote'],$data);

            if($result['code'] == 4000){
                returnJson($result);
            }
            //发送订单信息给货主
            sendMsg($orderInfo['sp_id'],self::SPTITLE,self::SPCONTENT,1);
            //发送推送消息
            $push_token = getSpPushToken($info['sp_id']);//得到推送token
            if(!empty($push_token)){
                pushInfo($push_token,self::SPTITLE,self::SPCONTENT,'wztx_shipper');//推送给司机
            }
            //发送短信
            sendSMS(getSpPhone($info['sp_id']),self::SPCONTENT,'wztx_shipper');
            returnJson(2000,'恭喜，您已获取该订单，请及时发货');
        }else{
            if($quote_time == 0){
                //第一次发送报价的价格给货主,取出货主phone
                $phone = getSpPhone($orderInfo['sp_id']);
                sendSMS($phone,'您的订单有新报价，价格为'.wztxMoney($paramAll['dr_price']),$rt_key='wztx_shipper');
            }

            //发送推送信息给货主
            $push_token = getSpPushToken($orderInfo['sp_id']);
            if(!empty($push_token)){
                pushInfo($push_token,self::TITLE,'您的订单有新报价，价格为'.wztxMoney($paramAll['dr_price']),$rt_key='wztx_shipper');//推送给货主端
            }
            returnJson($result);
        }
    }

    /**
     * @api {GET}   /order/receiveOrder     接收订单
     * @apiName receiveOrder
     * @apiGroup    Order
     * @apiHeader   {String}        authorization-token     token.
     * @apiSuccess  {String}         type    quote|order.
     *
     */
    public function receiveOrder(){
        //先判断该司机下是否设定了路线，有路线返回订单列表，无路线返回他的未报价列表
        $where = [];
        $lineInfo = $this->loginUser['id'];
    }
}