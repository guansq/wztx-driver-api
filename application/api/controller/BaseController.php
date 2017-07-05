<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/12
 * Time: 9:51
 */

namespace app\api\controller;

use jwt\JwtHelper;
use service\ToolsService;
use think\Controller;

class BaseController extends Controller{

    protected $getParams;
    protected $controller;
    protected $action;
    protected $loginUser;

    public function _initialize(){

        parent::_initialize();
        // CORS 跨域 Options 检测响应 允许跨域
        ToolsService::corsOptionsHandler();
        $this->getRequestInfo();
        $this->tokenGrantCheck();
    }


    /**
     * token检测
     */
    public function tokenGrantCheck(){

        //不需要token验证的控制器方法
        $except_controller = [
            "User" => ["login", "reg", "forget",'computeQlfScore'],
            "Index" => ["apiCode", 'lastApk', 'appConfig', 'sendCaptcha'],
            "U9Api" => [
                'initU9Data',
                'syncAll',
                "initSupplier",
                "syncSupplier",
                'initItem',
                'syncItem',
                'syncItemTrade',
                'syncSupItem',
                'syncPr',
                'initPr',
                'prToInquiry',
                'evaluateBid',
                'ioToPo',
                'syncPOState',
                'placeOrder'
            ]
        ];

        if(!array_key_exists($this->controller, $except_controller) || !in_array($this->action, $except_controller[$this->controller])){
            $token = request()->header('authorization-token', '');
            if(empty($token)){
                returnJson(4011);
            }
            $this->loginUser = JwtHelper::checkToken($token);

            $supplierInfo = model('SupplierInfo', 'logic')->findBySupId($this->loginUser['id']);
            if(empty($supplierInfo)){
                returnJson(4011);
            }
            $this->loginUser['sup_code'] = $supplierInfo['code'];
            $this->loginUser['sup_name'] = $supplierInfo['name'];
        }
    }

    /**
     * 获得get参数方法
     */
    protected function getRequestInfo(){
        $this->controller = request()->controller();
        $this->action = request()->action();
        $this->getParams = array_filter(input("param."), function($v){
            return $v != "";
        });

    }

    /**
     * 获得请求参参数
     */
    protected function getReqParams($keys = []){
        $params = input("param.");
        $ret = [];
        //        if(empty($params)){
        //            return [];
        //        }
        if(empty($keys)){
            return $params;
        }

        foreach($keys as $k => $v){
            if(is_numeric($k)){ // 一维数组
                $ret[$v] = array_key_exists($v, $params) ? $params[$v] : '';
                continue;
            }
            $ret[$k] = array_key_exists($k, $params) ? $params[$k] : (empty($v) ? '' : $v);
        }

        return $ret;
    }

    /**
     * 获得分页参参数
     */
    protected function getPagingParams(){

        $DEFAULT_PAGING_PARAMS = config('config_app.DEFAULT_PAGING_PARAMS');
        $pageParams = [
            'page' => input('page', $DEFAULT_PAGING_PARAMS['page']),
            'pageSize' => input('pageSize', $DEFAULT_PAGING_PARAMS['pageSize']),
        ];
        return $pageParams;
    }

    protected function checkLogin($encodeData){
        $this->getParams["lal"];
        return JwtHelper::checkToken($encodeData);
    }


    protected function flexClass(){
        $class = new \ReflectionClass($this);
        $flex_methods = (array)$class->getMethods(\ReflectionMethod::IS_PUBLIC);
        array_walk($flex_methods, function($v) use (&$methods){
            $current = (array)$v;
            if(trim($current["class"]) != __CLASS__ && trim($current["class"]) != "think\Controller"){
                //                $methods[basename($current["class"])] = $current;
                $methods[basename($current["class"])][] = $current["name"];
            }
        });
        return $methods;
    }

    /**
     * 参数效验
     */
    protected function paramCheck($guards){
        if(array_key_exists($this->action, $guards)){
            $paramDiff = implode(",", array_diff($guards[$this->action], array_keys($this->getParams)));
            if($paramDiff){
                returnJson(4011);
            }
        }

    }
}