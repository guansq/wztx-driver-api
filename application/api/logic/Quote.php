<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/18
 * Time: 16:11
 */
namespace app\api\logic;

class Quote extends BaseLogic{

    protected $table = 'rt_quote';

    /**
     * Auther: guanshaoqiu <94600115@qq.com>
     * Describe: 得到报价列表
     */
    public function geteQuoteList($where){
        $ret = $this->where($where)->select();
        if(empty($ret)){
            return resultArray(4000,'没有报价信息');
        }
        return resultArray(2000,'',$ret);
    }

    /**
     * Auther: guanshaoqiu <94600115@qq.com>
     * Describe: 得到单条报价信息
     */
    public function getQuoteInfo($where){
        $ret = $this->where($where)->find();
        if(empty($ret)){
            return resultArray(4000,'没有报价信息');
        }
        return resultArray(2000,'',$ret);
    }

    /**
     * Auther: guanshaoqiu <94600115@qq.com>
     * Describe:保存报价信息
     */
    public function saveQuoteInfo($where,$data){
        $ret = $this->where($where)->update($data);
        if($ret === false){
            return resultArray(4000,'保存报价失败');
        }
        return resultArray(2000,'保存报价信息成功');
    }
}