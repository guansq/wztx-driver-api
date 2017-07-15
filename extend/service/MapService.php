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
     * ['location','car_length','car_type','car_number']
     */
    public static function saveMapData($name,$location){
        $data = [
            'key' => getSysconf('map_key'),
            'tableid' => getSysconf('map_table_id'),
            'data' => json_encode([
                '_name' => $name,
                '_location' => $location,
                'car_length' => '100米',
                'car_type' => '长板',
                'car_number' => '苏E11111',
            ])
        ];
        //dump(http_build_query($data));die;
        //echo json_encode($data);die;
        $httpRet = HttpService::post('http://yuntuapi.amap.com/datamanage/data/create', http_build_query($data));
        if(empty($httpRet)){
            return resultArray(6000);
        }
        $ret = json_decode($httpRet, true);

        if(empty($ret)){
            return resultArray(6000,'',$httpRet);
        }
        dump($ret);die;
        if($ret['']){

        }
        //status info _id
        return resultArray($ret);
    }
}