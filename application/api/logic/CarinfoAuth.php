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
        if($ret){
            return $this->getLastInsID();
        }
        returnJson('4020', '抱歉，保存失败');
    }

    /*
     * 更新车辆验证信息
     */
    public function updateCarAuth($where,$data){
        $ret = $this->where($where)->update($data);
        if(!$ret){
            returnJson('4020', '抱歉，修改失败');
        }
    }
    /**
     * Describe:获取车辆验证的信息
     */
    public function getCarAuth($car_id){
        return $this->where("id",$car_id)->find();
    }
}