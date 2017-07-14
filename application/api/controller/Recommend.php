<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/11
 * Time: 10:34
 */
namespace app\api\controller;

use think\Request;

class Recommend extends BaseController{



    /**
     * @api {GET}   recommend/showMyRecommList      显示我的推荐列表
     * @apiName     showMyRecommList
     * @apiGroup    Recommend
     * @apiHeader   {String}    authorization-token         token.
     * @apiSuccess  {Array}     list                        列表
     * @apiSuccess  {String}    list.avatar                 被推荐人头像
     * @apiSuccess  {String}    list.name                   被推荐人名称
     * @apiSuccess  {String}    list.bonus                 奖励金
     */
    public function showMyRecommList(){

    }
}