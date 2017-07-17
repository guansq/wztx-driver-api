<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/15
 * Time: 17:14
 */
namespace app\api\logic;
use think\Db;
class CarinfoAuth extends BaseLogic{
    protected $table = 'rt_dr_carinfo_auth';
    /**
     * Auther: guanshaoqiu <94600115@qq.com>
     * Describe:保存车辆验证信息 并获得车辆验证ID
     */
    public function saveCarAuth($data){
        $ret = $this->allowField(true)->save($data);
        if($ret === false){
            returnJson('4020', '更新失败');
        }
        return $this->getLastInsID();
    }
}