<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 9:45
 */

namespace app\api\logic;

class Item extends BaseLogic{

    /**
     * Author: WILL<314112362@qq.com>
     * Describe:把U9数据同步到数据库
     */
    function initItem(){
        $params = ['StartTime' => 1];
        $u9Ret = Model('U9Api', 'logic')->httpGetItem($params);
        if($u9Ret['code'] != 2000){
            return resultArray($u9Ret);
        }
        $u9List = $u9Ret['result']['list'];
        if(empty($u9List)){
            return resultArray(4004);
        }

        return $this->insertItem($u9List);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe:把U9数据同步到数据库
     */
    function syncItem(){
        $u9Ret = Model('U9Api', 'logic')->httpGetItem();
        if($u9Ret['code'] != 2000){
            return resultArray($u9Ret);
        }
        $u9List = $u9Ret['result']['list'];
        if(empty($u9List)){
            return resultArray(4004);
        }

        return $this->insertItem($u9List);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe:把U9数据插入到数据库
     */
    function insertItem($u9List){
        $updatedCount = $createdCount = 0;
        foreach($u9List as $k => $v){
            if(!is_numeric($k)){
                $v = $u9List;
            }
            //是否存在
            $saveDate = [
                'code' => $v['ItemCode'],
                'name' => $v['ItemName'],
                'brand' => $v['TradeMark'],
                'main_code' => $v['MainItemCode'],
                'main_name' => $v['MainItemName'],
                'price_weight' => getSysconf('evaluate_price_weight', 0.55),
                'tech_weight' => getSysconf('evaluate_tech_weight', 0.45),
                'business_weight' => getSysconf('evaluate_biz_weight', 0.05),
                'pur_attr' => 'tech', // 默认技术型 影响评标

            ];

            if($this->exist($saveDate)){
                //trace('$u9SupModel isUpdate ===================true');
                $updatedCount += $this->isUpdate(true)->save($saveDate, ['code' => $saveDate['code']]);
            }else{
                //trace('$u9SupModel isUpdate ===================false');
                $createdCount += $this->isUpdate(false)->data($saveDate, true)->save();
            }

            if(!is_numeric($k)){
                break;
            }

        }
        $ret = [
            'u9Total' => $updatedCount+$createdCount,
            'updatedCount' => $updatedCount,
            'createdCount' => $createdCount
        ];
        return resultArray(2000,'',$ret);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe:把U9品牌物料交叉数据 同步到数据库
     */
    function syncItemTrade(){
        $u9Ret = Model('U9Api', 'logic')->httpGetItemTrade();
        if($u9Ret['code'] != 2000){
            return resultArray($u9Ret);
        }
        $u9List = $u9Ret['result']['list'];
        if(empty($u9List)){
            return resultArray(4004);
        }
        $count = 0;
        foreach($u9List as $v){
            //更新品牌
            $updateData = ['brand' => $v['TradeMark']];
            $where = ['code' => $v['ItemCode']];
            $count += $this->isUpdate(true)->save($updateData, $where);

        }
        $ret = [
            'u9Total' => count($u9List),
            'updatedCount' => $count
        ];
        return resultArray(2000, null, $ret);
    }

    /**
     * 判断U9的数据是否存在
     */
    public static function exist($data){
        $count = self::field('code')->where('code', $data['code'])->count();
        return $count == 1 ? true : false;//不存在true 存在false
    }

    /**
     * 根据code查找
     */
    public static function findByCode($code){
        return self::where('code', $code)->find();
    }

}