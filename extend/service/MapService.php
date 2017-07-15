<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/14
 * Time: 19:07
 */
namespace service;

/**
 * Class MapService
 * @package service
 */
class MapService{

    /**
     * Auther: guanshaoqiu <94600115@qq.com>
     * Describe:
     * http://yuntuapi.amap.com/datamanage/data/create
     */
    public static function saveMapData($name,$location){
        $data = [
            'key' => getSysconf('map_key'),
            'tableid' => getSysconf('map_table_id'),
            'data' => [
                '_name' => $name,
                '_location' => $location
            ]
        ];
        $httpRet = HttpService::post('http://yuntuapi.amap.com/datamanage/data/create', $data);
        if(empty($httpRet)){
            return resultArray(6000);
        }
        $ret = json_decode($httpRet, true);
        if(empty($ret)){
            return resultArray(6000,'',$httpRet);
        }

        //status info _id
        return resultArray($ret);
    }
}