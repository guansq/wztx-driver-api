<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/4
 * Time: 10:15
 */

namespace app\api\logic;


class SupplierInfo extends BaseLogic{

    public function findBySupId($supId, $fields = null){

        if(empty($fields)){
            return $this->where(['sup_id' => $supId])->find();
        }
        return $this->where(['sup_id' => $supId])->field($fields)->find();
    }

    public function findByCode($code){
        return $this->where(['code' => $code])->find();
    }

    /**
     * 判断数据是否存在
     */
    public static function exist($data){
        $count = self::field('code')->where('code', $data['code'])->count();
        return $count == 1 ? true : false;//不存在true 存在false
    }
}