<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/11
 * Time: 16:51
 */

namespace app\api\controller;

use think\Request;

class Comment extends BaseController {
    /**
     * @api {GET}   /comment/commentInfo    获取评论内容done
     * @apiName     commentInfo
     * @apiGroup    Comment
     * @apiHeader   {String}    authorization-token     token.
     * @apiParam    {Number}    order_id                订单ID
     * @apiSuccess  {Number}    order_id                订单ID
     * @apiSuccess  {Number}    sp_id                   评论人ID
     * @apiSuccess  {String}    sp_name                 评价人的姓名
     * @apiSuccess  {Number}    dr_id                   司机ID
     * @apiSuccess  {String}    dr_name                 司机姓名
     * @apiSuccess  {String}    post_time               提交时间
     * @apiSuccess  {String}    limit_ship              发货时效几星
     * @apiSuccess  {String}    attitude                服务态度几星
     * @apiSuccess  {String}    satisfaction            满意度 几星
     * @apiSuccess  {String}    content                 评论文字
     * @apiSuccess  {Int}    status                  0=正常显示，1=不显示给司机
     */
    public function commentInfo() {
        $paramAll = $this->getReqParams([
            'order_id',
        ]);
        $rule = [
            'order_id' => ['require', 'regex' => '^[0-9]*$'],
        ];
        validateData($paramAll, $rule);
        //获取订单评论详情
        $commetInfo = model('Comment', 'logic')->getOrderCommentInfo(['order_id' => $paramAll['order_id'], 'dr_id' => $this->loginUser['id']]);

        if (!empty($commetInfo)) {
            $commetInfo['post_time'] = wztxDate($commetInfo['post_time']);
            return returnJson(2000, '成功', $commetInfo);
        }
        returnJson(4004, '未获取到订单信息');
    }
}