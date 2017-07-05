<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 9:45
 */

namespace app\api\logic;

class BrandStop extends BaseLogic{

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 判断料号是否需要暂停询价
     */
    public function needPause($code){
        $itemLogic = model('Item', 'logic');
        $item = $itemLogic->findByCode($code);
        if(empty($item)){
            //trace("料号[$code]找不到");
            return true;
        }
        if($item['is_stop'] == 1){
            return true;
        }
        $brands = $this->where('is_enable', 1)->column('name');
        foreach($brands as $brand){
            if(strstr($item['brand'], $brand)){
                trace("料号[$code],品牌[$item[brand]],暂停品牌:$brand  要要要要要暂停询价");
                return true;
            }
            trace("料号[$code],品牌[$item[brand]],暂停品牌:$brand 不不不不不不不需要暂停询价");
        }
        //trace("料号[$code] 不不不不不不不需要暂停询价");
        return false;
    }

}