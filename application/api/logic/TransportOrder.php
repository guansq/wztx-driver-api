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
            $v['mind_price'] = wztxMoney( $v['mind_price']);
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
     *获取派发订单详情
     */
    public function getTransportOrderQuoteInfo($where) {
        $info = $this->alias('a')->field('a.*')->join('quote b','a.id = b.order_id','LEFT')->where($where)->find();
        return $info;
    }
    
    /**
     * Auther: guanshaoqiu <94600115@qq.com>
     * 更改订单信息
     */
    public function updateTransport($where, $data) {
        $ret = $this->where($where)->update($data);
        if ($ret === false) {
            return resultArray(4000, '更改订单状态失败');
        }
        return resultArray(2000, '更改订单状态成功');
    }

}