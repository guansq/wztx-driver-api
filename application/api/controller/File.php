<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/13
 * Time: 10:55
 */
namespace app\api\controller;

class File extends BaseController{
    // 上传文件 php 5.5
    function uploadFile(\think\File $file){

        //$info = $file->move('/tmp','wztx_tmp_avatar.png');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
        //$filePath = ROOT_PATH . 'public' . DS . 'upload'. DS .$info->getSaveName();
        $data = [
            'rt_appkey' => 'wztx',
            'file' => '@'.$info->getPathname()
        ];

        $return_data = HttpService::post($this->url, $data);
        if(empty($return_data)){
            return resultArray(6001);
        }
        $ossRet = json_decode($return_data,true);
        if(empty($ossRet)){
            return resultArray(6001,'上传图片错误','');
        }
        if($ossRet['code'] !=2000){
            return resultArray(6001,$ossRet['msg'],'');
        }

        return resultArray($ossRet);
    }
}