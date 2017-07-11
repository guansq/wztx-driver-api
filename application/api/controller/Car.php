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
     * @api     {GET} /car/getAllCarStyle       获取车辆车长信息以及车型
     * @apiName     getAllCarStyle
     * @apiGroup    Car
     * @apiHeader   {String} authorization-token           token.
     * @apiSuccess  {Array}  list                          车辆信息数组
     * @apiSuccess  {Array}  list.length                   车辆长度信息数组
     * @apiSuccess  {Array}  list.type                     车辆类型信息数组
     */
    public function getAllCarStyle(){

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