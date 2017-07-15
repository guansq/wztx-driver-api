<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/13
 * Time: 15:28
 */
namespace app\api\logic;
use think\Db;
class Car extends BaseLogic{
    protected $table = 'rt_car_style';

    /**
     * Auther: guanshaoqiu <94600115@qq.com>
     * Describe:获取车型信息
     */
    public function getCarInfo(){
        $where = [
            //"type" => $type,
            "status" => 0
        ];
        $ret = $this->where($where)->select();
        $type = [1=>'type',2=>'length'];

        if(!$ret){
            return resultArray('4000','数据为空');
        }
        $newArr = [];
        $ret = collection($ret)->toArray();
        foreach($ret as $k=>$v){
            $newArr[$type[$v['type']]][] = $v;
        }
        //dump($newArr);die;
        return resultArray('2000','成功',$newArr);
    }

    /**
     * Auther: guanshaoqiu <94600115@qq.com>
     * Describe:得到车辆信息
     */
    public function findCarInfoById($carId){
        $where = [
            'id' => $carId,
            'status' => 0
        ];
        $ret = $this->where($where)->find();
        if(empty($ret)){
            returnJson('4000','暂无车辆信息');
        }
        return $ret;
    }
}