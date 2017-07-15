<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/7
 * Time: 10:34
 */
namespace app\api\controller;

class Car extends BaseController{
    /**
     * @api     {GET} /car/getAllCarStyle       获取车辆车长信息以及车型done
     * @apiName     getAllCarStyle
     * @apiGroup    Car
     * @apiSuccess  {Array}  length                 车长数组
     * @apiSuccess  {Array}  type                   车型数组
     * @apiSuccess  {String} length-type.name                   名称
     * @apiSuccess  {Number} length-type.type                   1=车型，2=车长
     * @apiSuccess  {Number} length-type.status                 0=正常，1=删除
     * @apiSuccess  {String} length-type.over_metres_price      超出起步公里费
     * @apiSuccess  {String} length-type.weight_price           计重费
     * @apiSuccess  {String} length-type.init_kilometres        起步公里数
     * @apiSuccess  {String} length-type.init_price             车长-起步价
     */
    public function getAllCarStyle(){

        //dump($paramAll);die;
        $result = model('Car','logic')->getCarInfo();
        returnJson($result);
    }

    /**
      * @api     {GET} /car/getOneCarStyle       获取单个车辆信息
      * @apiName     getOneCarStyle
      * @apiGroup    Car
      * @apiHeader   {String} authorization-token           token.
      *
      * @apiSuccess  {String}  card_number                  车牌号
      * @apiSuccess  {String}  length                       车型
      * @apiSuccess  {String}  type                         车长
      */
    public function getOneCarStyle(){

    }

    /**
     * @api {get} /test/test 测试
     * @apiName test
     * @apiGroup Test
     *
     * @apiParam {Number} id Users unique ID.
     *
     * @apiSuccess {String} firstname Firstname of the User.
     * @apiSuccess {String} lastname  Lastname of the User.
     */
    public function test(){

    }
}