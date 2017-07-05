<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 9:45
 */

namespace app\api\logic;

class SupItem extends BaseLogic{

    protected $table ='atw_u9_sup_item';

    /**
     * Author: WILL<314112362@qq.com>
     * Describe:把U9数据同步到数据库
     */
    function syncSupItem(){
        $u9Ret = Model('U9Api', 'logic')->httpGetSupItem();
        if($u9Ret['code'] != 2000){
            return resultArray($u9Ret);
        }
        $u9List = $u9Ret['result']['list'];
        if(empty($u9List)){
            return resultArray(4004);
        }
        // 清空表
        $this->where('1=1')->delete();
        $saveList =[];
        foreach($u9List as $v){
            //是否存在
            $saveDate = [
                'item_code' => $v['ItemCode'],
                'item_name' => $v['ItemName'],
                'sup_code' => $v['SupCode'],
                'sup_name' => $v['SupName'],
            ];
            $saveList[] =$saveDate;
        }

        $this->saveAll($saveList);
        $ret = [
            'u9Total' => count($u9List),
        ];
        return resultArray(2000,'',$ret);
    }


}