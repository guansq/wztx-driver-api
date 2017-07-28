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
     * Describe: 保存订单信息
     */
    public function saveTransportOrder($param) {
        //$param['system_price'] = '2017.02';
        $ret = $this->allowField(true)->save($param);
        if ($ret > 0) {
            $order_id = $this->getLastInsID();
            return resultArray(2000, '成功', ['order_id' => $order_id]);
        }
        return resultArray(4000, '保存订单失败');
    }

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
            ->field('*,id order_id')->select();

        if(empty($list)){
            return false;
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
        //$info = $this->alias('a')->field('a.*')->join('quote b','a.id = b.order_id','LEFT')->where($where)->find();
        $info = $this->where($where)->find();
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
    //获取成功订单信息
    public function  getSuccessListInfo($where){
        $list = $this->alias('a')->where($where)->field(' FROM_UNIXTIME(pay_time,"%Y-%m") month,count(id) order_amount,sum(final_price) tran_total')->group('month')->select();
        //echo $this->getLastSql();
        return $list;
    }
    //获取未结算订单
    public function getUnbalanced($where){
        $list = $this->alias('a')->where($where)->field('count(id) order_amount,sum(final_price) tran_total')->select();
        //echo $this->getLastSql();
        if ($list) {
            $list = collection($list)->toArray();
        }
        return $list;
    }

}