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
     * @api {POST}  /quote/add      提交司机报价done
     * @apiName     add
     * @apiGroup    Quote
     * @apiHeader   {String}    authorization-token     token.
     * @apiParam    {String}       quote_id                报价ID。
     * @apiParam    {String}     dr_price                司机出价
     * @apiParam    {String}     is_place_order          是否立即下单
     */
    public function add(){
        $paramAll = $this->getReqParams(['quote_id','dr_price']);
        $rule = ['quote_id' => 'require'];
        //dump($paramAll);
        validateData($paramAll,$rule);
        $data = [
            'status' => 'quote',
            'dr_price' => wztxMoney($paramAll['dr_price'])
        ];
        $where = [
            'id' => $paramAll['quote_id'],
            'dr_id' => $this->loginUser['id']
        ];
        //是否是第一次报价的司机 如果是第一次 发送短信给货主count(*) order =
        $info = model('Quote','logic')->getQuoteInfo(['id'=>$paramAll['quote_id']]);
        if(empty($info)){
            returnJson(4000,'抱歉暂无报价所对应的订单信息');
        }
        $info = $info['result'];
        //dump(collection($info)->toArray());die;
        $quote_time = model('Quote','logic')->findOneQuote(['order_id'=>$info['order_id'],'status'=>'quote']);//该订单的报价次数
        if($quote_time == 0){
            //第一次发送报价的价格给货主,取出货主phone
            $phone = getSpPhone($info['sp_id']);
            sendSMS($phone,'您的订单有新报价，价格为'.wztxMoney($paramAll['dr_price']),$rt_key='wztx_shipper');
        }
        $result = model('Quote','logic')->saveQuoteInfo($where,$data);
        //发送推送信息给货主
        $push_token = getSpPushToken($info['sp_id']);
        if(!empty($push_token)){
            pushInfo($push_token,self::TITLE,'您的订单有新报价，价格为'.wztxMoney($paramAll['dr_price']),$rt_key='wztx_shipper');//推送给货主端
        }
        returnJson($result);
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
}