<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/18
 * Time: 16:17
 */

namespace app\api\logic;

class SystemBanner extends BaseLogic{

    public function getBannerList(){
        return self::field('id,name as title ,url as link ,src as img,sort')->order('sort')->select();
    }
}
