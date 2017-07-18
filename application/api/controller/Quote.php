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
            'quote_id' => 'request',
        ];
        validateData($paramAll,$rule);
        $where = ['id'=>$paramAll['quote_id']];
        $result = model('Quote','logic')->saveQuoteInfo($where);
        returnJson($result);
    }

    /**
     * @api {POST}  /quote/add      提交司机报价done
     * @apiName     add
     * @apiGroup    Quote
     * @apiHeader   {String}    authorization-token     token.
     * @apiParam    {Int}       quote_id                报价ID。
     * @apiParam    {Float}     dr_price                司机出价
     */
    public function add(){
        $paramAll = $this->getReqParams(['quote_id','dr_price']);
        $rule = [
            'quote_id' => 'request',
            'dr_price' => 'request'
        ];
        validateData($paramAll,$rule);
        $data = [
            'status' => 'quote',
            'dr_price' => wztxMoney($paramAll['dr_price'])
        ];
        $where = [
            'id' => $paramAll['quote_id']
        ];
        $result = model('Quote','logic')->saveQuoteInfo($where,$data);
        returnJson($result);
    }

    /**
     * @api {GET}   /quote/quoteList     司机报价列表done
     * @apiName     quoteList
     * @apiGroup    Quote
     * @apiHeader   {String}    authorization-token     token.
     * @apiParam    {String}    status          all所有,init未报价,quote已报价
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
        $rule = ['status' => 'require'];
        validateData($paramAll,$rule);

        $where = [
            'dr_id' => $this->loginUser['id'],
        ];
        if(in_array($paramAll['status'],['init','quote'])){
            $where['status'] = $paramAll['status'];
        }
        $result = model('Quote','logic')->geteQuoteList($where);
        returnJson($result);
    }
}