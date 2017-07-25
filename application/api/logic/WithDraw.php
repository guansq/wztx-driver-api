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
     * Auther: guanshaoqiu <94600115@qq.com>
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


}