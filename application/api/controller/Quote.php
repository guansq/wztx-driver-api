<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/11
 * Time: 9:09
 */
namespace app\api\controller;

use think\Request;

class Quote extends BaseController{
    const TITLE = '您有新的司机报价';
    const SPTITLE = '您的订单已被接单';
    const SPCONTENT = '您的订单已被接单';
    /**
     * @api {GET}    /quote/getInfo     获得报价信息done
     * @apiName     getInfo
     * @apiGroup    Quote
     * @apiHeader   {String}  authorization-token     token.
     * @apiParam    {Int}   quote_id        报价ID
     * @apiSuccess  {String}    org_city  起始地
     * @apiSuccess  {String}    dest_city 目的地
     * @apiSuccess  {String}    goods_name      货品名称
     * @apiSuccess  {String}    weight          货品重量
     * @apiSuccess  {String}    system_price    系统出价
     * @apiSuccess  {String}    sp_price        货主出价
     * @apiSuccess  {String}    usecar_time     用车时间
     */
    public function getInfo(){
        $paramAll = $this->getReqParams(['quote_id']);
        $rule = [
            'quote_id' => 'require',
        ];
        validateData($paramAll,$rule);
        $where = ['id'=>$paramAll['quote_id']];
        $result = model('Quote','logic')->getQuoteInfo($where);
        returnJson($result);
    }


    /**
     * @api {POST}  /quote/saveQuote        提交货源报价done
     * @apiName saveQuote
     * @apiGroup    Order
     * @apiHeader   {String}    authorization-token     token.
     * @apiParam    {String}     goods_id                货源ID。
     * @apiParam    {String}     dr_price                司机出价
     * @apiParam    {String}     is_receive          是否立即下单 0表示不立即下单 1表示立即下单
     */
    public function saveQuote(){
        //判断订单ID是否合法通过订单ID生成已报价的报价单（如果立即下单，更改订单，价格状态）->判断该订单下面的询价单是否有报价过，无报价发送短信->
        $paramAll = $this->getReqParams(['goods_id','dr_price','is_receive']);
        $rule = ['goods_id' => 'require','dr_price' => 'require','is_receive'=>'require'];
        validateData($paramAll,$rule);
        $goodsInfo = model('Goods','logic')->getGoodsInfo(['status'=>'quote','id'=>$paramAll['goods_id']]);//待报价货源
        if(empty($goodsInfo)){
            returnJson(4000,'没有该货源信息，或该货源已被下单');
        }

        //查询是否是第一次报价
        $quote_time = model('Quote','logic')->findOneQuote(['goods_id'=>$paramAll['goods_id']]);//该订单的报价次数,'status'=>'quote'

        $info['goods_name'] = $goodsInfo['goods_name'];
        $info['weight'] = $goodsInfo['weight'];
        $info['goods_id'] = $goodsInfo['id'];
        $info['dr_id'] = $this->loginUser['id'];
        $info['sp_id'] = $goodsInfo['sp_id'];
        $info['system_price'] = $goodsInfo['system_price'];
        $info['sp_price'] = $goodsInfo['mind_price'];
        $info['dr_price'] = $paramAll['dr_price'];
        $info['is_receive'] = $paramAll['is_receive'];
        $info['usecar_time'] = $goodsInfo['usecar_time'];
        $info['car_style_length'] = $goodsInfo['car_style_length'];
        $info['car_style_type'] = $goodsInfo['car_style_type'];
        $info['org_city'] = $goodsInfo['org_city'];
        $info['dest_city'] = $goodsInfo['dest_city'];
        $info['org_address_name'] = $goodsInfo['org_address_name'];
        $info['dest_address_name'] = $goodsInfo['dest_address_name'];
        $info['org_address_detail'] = $goodsInfo['org_address_detail'];
        $info['dest_address_detail'] = $goodsInfo['dest_address_detail'];
        $result = model('Quote','logic')->saveQuote($info);//生成报价单

        if($paramAll['is_receive'] == 1){
            //更改货源状态
            $data = [
                'status' => 'quoted',
                'final_price' => $paramAll['dr_price'],
                'dr_id' => $this->loginUser['id'],
            ];
            //更改货源状态为已被接单状态
            $result = model('Goods','logic')->updateGoods(['id'=>$paramAll['goods_id'],'sp_id'=>$info['sp_id'],'status'=>'quote'],$data);

            if($result['code'] == 4000){
                returnJson($result);
            }
            //生成订单
            $result = $this->saveOrderBygoodsInfo($paramAll['goods_id']);
            if($result['code'] == 4000){
                returnJson($result);
            }
            //发送订单信息给货主
            sendMsg($goodsInfo['sp_id'],self::SPTITLE,self::SPCONTENT,1);
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
                $phone = getSpPhone($goodsInfo['sp_id']);
                sendSMS($phone,'您的订单有新报价，价格为'.wztxMoney($paramAll['dr_price']),$rt_key='wztx_shipper');
            }

            //发送推送信息给货主
            $push_token = getSpPushToken($goodsInfo['sp_id']);
            if(!empty($push_token)){
                pushInfo($push_token,self::TITLE,'您的订单有新报价，价格为'.wztxMoney($paramAll['dr_price']),$rt_key='wztx_shipper');//推送给货主端
            }
            returnJson($result);
        }
    }
    /**
     * @api {GET}   /quote/quoteList     司机报价列表done
     * @apiName     quoteList
     * @apiGroup    Quote
     * @apiHeader   {String}    authorization-token     token.
     * @apiParam    {String}    status          all所有,init未报价,quote已报价
     * @apiSuccess  {String}    id  报价ID
     * @apiSuccess  {String}    org_city  起始地
     * @apiSuccess  {String}    dest_city 目的地
     * @apiSuccess  {String}    goods_name      货品名称
     * @apiSuccess  {String}    weight          货品重量
     * @apiSuccess  {String}    system_price    系统出价
     * @apiSuccess  {String}    sp_price        货主出价
     * @apiSuccess  {String}    usecar_time     用车时间
     */
    public function quoteList(){
        $paramAll = $this->getReqParams(['status']);
        $pageParam = $this->getPagingParams();
        $rule = ['status' => 'require'];
        validateData($paramAll,$rule);

        $where = [
            'dr_id' => $this->loginUser['id'],
        ];
        if(in_array($paramAll['status'],['init','quote'])){
            $where['status'] = $paramAll['status'];
        }
        $result = model('Quote','logic')->geteQuoteList($where,$pageParam);

        returnJson($result);
    }


    /**
     * Auther: guanshaoqiu <94600115@qq.com>
     * Describe:根据货源信息保存订单
     */
    private function saveOrderBygoodsInfo($goods_id){
        //根据$goods_id取出信息
        $goodsInfo = model('Goods','logic')->getGoodsInfo(['id'=>$goods_id]);
        //生成订单
        $orderInfo['order_code'] = order_num();
        $orderInfo['sp_id'] = $goodsInfo['sp_id'];
        $orderInfo['type'] = $goodsInfo['type'];
        $orderInfo['appoint_at'] = $goodsInfo['appoint_at'];
        $orderInfo['insured_amount'] = $goodsInfo['insured_amount'];
        $orderInfo['premium_amount'] = $goodsInfo['premium_amount'];
        $orderInfo['org_send_name'] = $goodsInfo['org_send_name'];
        $orderInfo['org_address_maps'] = $goodsInfo['org_address_maps'];
        $orderInfo['org_city'] = $goodsInfo['org_city'];
        $orderInfo['org_address_name'] = $goodsInfo['org_address_name'];
        $orderInfo['org_address_detail'] = $goodsInfo['org_address_detail'];
        $orderInfo['org_phone'] = $goodsInfo['org_phone'];
        $orderInfo['org_telphone'] = $goodsInfo['org_telphone'];
        $orderInfo['dest_receive_name'] = $goodsInfo['dest_receive_name'];
        $orderInfo['dest_address_maps'] = $goodsInfo['dest_address_maps'];
        $orderInfo['dest_city'] = $goodsInfo['dest_city'];
        $orderInfo['dest_address_name'] = $goodsInfo['dest_address_name'];
        $orderInfo['dest_address_detail'] = $goodsInfo['dest_address_detail'];
        $orderInfo['dest_phone'] = $goodsInfo['dest_phone'];
        $orderInfo['dest_telphone'] = $goodsInfo['dest_telphone'];
        $orderInfo['goods_name'] = $goodsInfo['goods_name'];
        $orderInfo['volume'] = $goodsInfo['volume'];
        $orderInfo['weight'] = $goodsInfo['weight'];
        $orderInfo['car_style_type'] = $goodsInfo['car_style_type'];
        $orderInfo['car_style_type_id'] = $goodsInfo['car_style_type_id'];
        $orderInfo['car_style_length'] = $goodsInfo['car_style_length'];
        $orderInfo['car_style_length_id'] = $goodsInfo['car_style_length_id'];
        $orderInfo['effective_time'] = $goodsInfo['effective_time'];
        $orderInfo['mind_price'] = $goodsInfo['mind_price'];
        $orderInfo['final_price'] = $goodsInfo['final_price'];
        $orderInfo['system_price'] = $goodsInfo['system_price'];
        $orderInfo['payway'] = $goodsInfo['payway'];
        $orderInfo['is_pay'] = $goodsInfo['is_pay'];
        $orderInfo['remark'] = $goodsInfo['remark'];
        $orderInfo['tran_type'] = $goodsInfo['tran_type'];
        $orderInfo['usecar_time'] = $goodsInfo['usecar_time'];
        $orderInfo['kilometres'] = $goodsInfo['kilometres'];
        $orderInfo['status'] = 'quoted';
        //完善个人信息填写  sp_id
        $baseUserInfo = getBaseSpUserInfo($goodsInfo['sp_id']);
        $orderInfo['real_name'] = $baseUserInfo['real_name'];
        $orderInfo['company_name'] = getCompanyName($baseUserInfo);
        $orderInfo['customer_type'] = $baseUserInfo['type'];
        $address = explode(',',$goodsInfo['org_address_maps']);
        $orderInfo['org_longitude'] = $address[0];
        $orderInfo['org_latitude'] = $address[1];
        $address = explode(',',$goodsInfo['dest_address_maps']);
        $orderInfo['dest_longitude'] = $address[0];
        $orderInfo['dest_latitude'] = $address[1];
        $result = model('TransportOrder','logic')->saveTransportOrder($orderInfo);
        return $result;
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
        $where = ['dr_id'=> $this->loginUser['id'],'status'=>0];
        $lineInfo = model('Linelist','logic')->getDrLineList($where);
        if($lineInfo['code'] == 4000){//获取报价列表

        }else{//获取订单列表
            $pageParam = $this->getPagingParams();
            $list = $lineInfo['result']['list'];
            $transportLogic = model('TransportOrder','logic');
            $quote = [];
            foreach($list as $k =>$v){
                $where = [
                    'status' => 'quote',
                    'org_city' => ['like',"%{$v['org_city']}%"],
                    'dest_city' => ['like',"%{$v['dest_city']}%"]
                ];
            }
        }
    }
}