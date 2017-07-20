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
    public function geteQuoteList($where,$pageParam){
        $list = [];
        $dataTotal = $this->where($where)->count();
        $list = $this->where($where)->page($pageParam['page'], $pageParam['pageSize'])->select();
        if(empty($dataTotal)){
            return resultArray(4004,'没有报价信息');
        }
        $ret = [
            'list' => $list,
            'page' => $pageParam['page'],
            'pageSize' => $pageParam['pageSize'],
            'dataTotal' => $dataTotal,
            'pageTotal' => floor($dataTotal/$pageParam['pageSize']) + 1,
        ];

        return resultArray(2000, '', $ret);
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

    /**
     * Auther: guanshaoqiu <94600115@qq.com>
     * Describe:找出自己是否是第一次报价
     */
    public function findOneQuote($where){
        return $this->where($where)->count();
    }
}