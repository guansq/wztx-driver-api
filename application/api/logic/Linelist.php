<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/7
 * Time: 10:34
 */

namespace app\api\logic;

class Linelist extends BaseLogic {
    protected $table = 'rt_dr_linelist';

    /**
     * Describe: 保存路线信息
     */
    public function saveDrLinelist($param) {
        $ret = $this->allowField(true)->save($param);
        if ($ret > 0) {
            $order_id = $this->getLastInsID();
            return resultArray(2000, '成功', ['drline_id' => $order_id]);
        }
        return resultArray(4000, '保存订单失败');
    }

    /**
     * Describe:获取路线信息
     */
    public function getDrLineList($where = []) {
        $where["status"] = 0;
        $ret = $this->where($where)->field("id drline_id,org_city,dest_city")->select();
        if (!$ret) {
            return resultArray('4000', '数据为空');
        }
        return ['list' => $ret];
    }

    /**
     * Describe:删除路线信息
     */
    public function delDrLineList($where = []) {
        $data["status"] = 1;
        $ret = $this->where($where)->update($data);
        if ($ret === false) {
            return resultArray(4000, '删除路线失败');
        }
        return resultArray(2000, '删除路线成功');
    }

}