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
    public static function saveMapData($mapInfo){
        $data = [
            'key' => getSysconf('map_key'),
            'tableid' => getSysconf('map_table_id'),
            'data' => json_encode($mapInfo)
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
        if($ret['infocode'] == 10000){
            return $ret['_id'];
        }else{
            returnJson(6000,$ret['infocode'],$ret);
        }
    }

    /*
     * 得到司机当前的坐标
     * http://yuntuapi.amap.com/datasearch/id?tableid=596892e77bbf190cbd32058b&_id=22&key=3a74da0c1de0f653254f82f4c0182877
     */
    public static function getCurLocal($mapCode){
        $data = [
            'key' => getSysconf('map_key'),
            'tableid' => getSysconf('map_table_id'),
            '_id' => $mapCode,
        ];
        $httpRet = HttpService::get('http://yuntuapi.amap.com/datasearch/id', http_build_query($data));
        if(empty($httpRet)){
            return resultArray(6000);
        }
        $ret = json_decode($httpRet, true);

        if(empty($ret)){
            return resultArray(6000,'',$httpRet);
        }
        if($ret['infocode'] == 10000){
            return $ret['datas'];
        }else{
            returnJson(6000,$ret['infocode'],$ret);
        }
    }
}