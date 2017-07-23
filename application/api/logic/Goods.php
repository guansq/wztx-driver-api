<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/23
 * Time: 12:55
 */
namespace app\api\logic;
use think\Db;
class Goods extends BaseLogic{
    protected $table = 'rt_goods';


    //获取货源列表
    public function getGoodsList($where, $pageParam) {
        $dataTotal = $this->where($where)->order('create_at desc')->count();
        if (empty($dataTotal)) {
            return false;
        }
        if(!empty($where)){
            $list = $this->where($where)->order('create_at desc')->page($pageParam['page'], $pageParam['pageSize'])
                ->field('id order_id,org_city,mind_price,system_price,dest_city,weight,goods_name,status,car_style_length,car_style_type,final_price,usecar_time')->select();
        }else{
            $list = $this->order('create_at desc')->page($pageParam['page'], $pageParam['pageSize'])
                ->field('id order_id,org_city,mind_price,system_price,dest_city,weight,goods_name,status,car_style_length,car_style_type,final_price,usecar_time')->select();
        }
        if(empty($list)){
            return returnJson(4000,'暂无数据');
        }
        foreach ($list as $k =>$v){
            $v['weight'] =strval($v['weight']);
            $v['final_price']= wztxMoney( $v['final_price']);
            $v['sp_price'] = wztxMoney( $v['mind_price']);
            $v['system_price'] = wztxMoney( $v['system_price']);
            $v['usecar_time'] =wztxDate($v['usecar_time']);
        }
        $ret = [
            'list' => $list,
            'page' => $pageParam['page'],
            'pageSize' => $pageParam['pageSize'],
            'dataTotal' => $dataTotal,
            'pageTotal' => intval(($dataTotal + $pageParam['pageSize'] - 1) / $pageParam['pageSize']),
        ];
        return $ret;
    }

    /**
     * Auther: guanshaoqiu <94600115@qq.com>
     * Describe: * @param $where
     * 得到单个货源信息
     */
    public function getGoodsInfo($where) {
        $ret = $this->where($where)->find();
        return $ret;
    }


    /**
     * Auther: guanshaoqiu <94600115@qq.com>
     * 更改货源信息
     */
    public function updateGoods($where, $data) {
        $ret = $this->where($where)->update($data);
        if ($ret === false) {
            return resultArray(4000, '更改货源状态失败');
        }
        return resultArray(2000, '更改货源状态成功');
    }
}