<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/13
 * Time: 15:28
 */
namespace app\api\logic;
use think\Db;
class Advertisement extends BaseLogic{
    protected $table = 'rt_advertisement';

    //获得广告信息
    public function getAdInfo(){
        $now = time();
        $where = [
            "status" => 0,
            "begintime"=>['<=',$now],
            "endtime"=>['>=',$now],
            "port" =>1,
        ];
        $ret = $this->where($where)->field("id,position,src,url")->order("position asc")->select();
        return $ret;
    }
}