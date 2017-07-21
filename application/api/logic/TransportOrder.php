<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/14
 * Time: 15:26
 */
namespace app\api\logic;
use think\Db;
class TransportOrder extends BaseLogic {
    protected $table = 'rt_transport_order';

    /**
     * Auther: guanshaoqiu <94600115@qq.com>
     * Describe: * @param $where
     * 得到单个订单信息
     */
    public function getTransportOrderInfo($where) {
        $ret = $this->where($where)->find();
        return $ret;
    }

    //获取订单详情
    public function getTransportOrderList($where, $pageParam) {
        $dataTotal = $this->where($where)->order('create_at desc')->count();
        if (empty($dataTotal)) {
            return false;
        }
        $list = $this->where($where)->order('create_at desc')->page($pageParam['page'], $pageParam['pageSize'])
            ->field('id order_id,org_city,dest_city,weight,goods_name,status,car_style_length,car_style_type,final_price,usecar_time')->select();
        foreach ($list as $k =>$v){
            $v['weight'] =strval($v['weight']);
            $v['final_price']= wztxMoney( $v['final_price']);
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

}