<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/14
 * Time: 15:26
 */
namespace app\api\logic;
use think\Db;
class WithDraw extends BaseLogic {
    protected $table = 'rt_withdraw';

    /**
     * Describe: 保存提现信息
     */
    public function saveWithDraw($param) {
        $ret = $this->allowField(true)->save($param);
        if ($ret > 0) {
            $withdraw_id = $this->getLastInsID();
            return resultArray(2000, '成功', ['withdraw_id' => $withdraw_id]);
        }
        return resultArray(4000, '保存提现信息失败');
    }

    /**
     * Describe: 获取提现中
     */
    public function getWithDrawList($where,$pageParam) {
        $dataTotal =  $this->where($where)->order('create_at desc')->count();
        if (empty($dataTotal)) {
            return false;
        }
        $list =  $this->where($where)->order('create_at desc')->page($pageParam['page'], $pageParam['pageSize'])
            ->select();

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
}