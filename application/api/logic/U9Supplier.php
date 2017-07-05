<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 9:45
 */
namespace app\api\logic;

class U9Supplier extends BaseLogic{

    /**
     * 取得U9信息
     */
    public function getListInfo(){
        $list =  $this->select();
        if($list) {
            $list = collection($list)->toArray();
        }
        return $list;
    }

    /**
     * 判断U9的数据是否存在
     */
    public static function exist($data){
        $count = self::field('code')->where('code', $data['code'])->count();
        return $count == 1 ? true : false;//不存在true 存在false
    }
}