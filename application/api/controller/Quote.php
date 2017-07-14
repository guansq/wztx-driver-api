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
     * @api {GET}    /quote/getInfo     获得报价信息
     * @apiName     getInfo
     * @apiGroup    Quote
     * @apiHeader   {String}  authorization-token     token.
     * @apiParam    {Int}   quote_id        报价ID
     * @apiSuccess  {String}    org_short_name  起始地
     * @apiSuccess  {String}    dest_short_name 目的地
     * @apiSuccess  {String}    goods_name      货品名称
     * @apiSuccess  {String}    weight          货品重量
     * @apiSuccess  {String}    system_price    系统出价
     * @apiSuccess  {String}    sp_price        货主出价
     * @apiSuccess  {String}    usecar_time     用车时间
     */
    public function getInfo(){

    }

    /**
     * @api {POST}  /quote/add      提交司机报价
     * @apiName     add
     * @apiGroup    Quote
     * @apiHeader   {String}    authorization-token     token.
     * @apiParam    {Int}       quote_id                报价ID。
     * @apiParam    {Float}     dr_price                司机出价
     */
    public function add(){

    }

    /*
     * @api {GET}   /quote/quoteList     司机报价列表
     * @apiName     quoteList
     * @apiGroup    Quote
     * @apiHeader   {String}        authorization-token     token.
     * @apiSuccess  {String}    org_short_name  起始地
     * @apiSuccess  {String}    dest_short_name 目的地
     * @apiSuccess  {String}    goods_name      货品名称
     * @apiSuccess  {String}    weight          货品重量
     * @apiSuccess  {String}    system_price    系统出价
     * @apiSuccess  {String}    sp_price        货主出价
     * @apiSuccess  {String}    usecar_time     用车时间
     */
    public function quoteList(){

    }
}