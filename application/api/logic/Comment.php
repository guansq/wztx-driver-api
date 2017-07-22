<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/14
 * Time: 15:26
 */

namespace app\api\logic;

class Comment extends BaseLogic{
    /**
     * 得到单个订单评论信息
     */
    public function getOrderCommentInfo($where){
        $ret = $this->where($where)->field("order_id,sp_id,sp_name,dr_id,dr_name,post_time,limit_ship,attitude,satisfaction,content,status")->find();
       return $ret;
    }
}