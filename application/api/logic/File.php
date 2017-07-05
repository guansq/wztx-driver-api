<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 9:45
 */

namespace app\api\logic;

use service\HttpService;

class File extends BaseLogic{

    public $url = 'http://oss.ruitukeji.com/index/uploadFiles';

    // 上传文件 php 5.5
    function uploadFile(\think\File $file){

        $info = $file->move('/tmp','atwwg_tmp_avatar.png');
        $data = [
            'rt_appkey' => 'atw_wg',
            'file' => '@'.$info->getPathname()
        ];

        $return_data = HttpService::post($this->url, $data);
        if(empty($return_data)){
            return resultArray(6001);
        }
        $ossRet = json_decode($return_data,true);
        if(empty($ossRet) || $ossRet['code'] !=2000){
            return resultArray(6001,'',$ossRet);
        }
        return resultArray($ossRet);
    }


}