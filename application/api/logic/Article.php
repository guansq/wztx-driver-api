<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/13
 * Time: 15:28
 */
namespace app\api\logic;
use think\Db;
class Article extends BaseLogic{
    protected $table = 'rt_system_article';

    //获得文章内容
    public function getArticleInfo($type=''){
        if(empty($type)){
            return false;
        }
        $where = [
            "type" => $type,
        ];
        $ret = $this->where($where)->field("title,content,type")->find();
        $ret['content']="<style>img{max-width:95%;height: auto;}</style>" . $ret['content'];
        return $ret;
    }
}