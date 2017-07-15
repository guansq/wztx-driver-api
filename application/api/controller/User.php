<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/12
 * Time: 9:55
 */

namespace app\api\controller;

use think\Request;
use service\MsgService;
use service\MapService;

class User extends BaseController{

    /**
     * @api     {POST} /User/reg            用户注册done
     * @apiName   reg
     * @apiGroup  User
     * @apiParam {String} user_name             手机号/用户名.
     * @apiParam {String} password          加密的密码. 加密方式：MD5("RUITU"+明文密码+"KEJI")
     * @apiParam {String} captcha           验证码.
     * @apiParam {String} [recomm_code]   推荐码
     * @apiParam {String} pushToken        推送使用的Token
     * @apiSuccess {Number} userId          用户id.
     * @apiSuccess {String} accessToken     接口调用凭证.
     */
    public function reg(Request $request){
        //校验参数
        $paramAll = $this->getReqParams(['type','user_name', 'password', 'recomm_code', 'pushToken', 'captcha']);
        //$result=$this->validate($paramAll,'User');
        $rule = [
            'user_name'=>['regex'=>'/^[1]{1}[3|5|7|8]{1}[0-9]{9}$/','require','unique:system_user_driver'],
            'password' => 'require|length:6,128',
            'captcha' => 'require|length:4,8',
        ];
        validateData($paramAll, $rule);
        //校验验证码
        $result = MsgService::verifyCaptcha($paramAll['user_name'],'reg',$paramAll['captcha']);
        if($result['code'] != 2000){
            returnJson($result);
        }
        //写入数据库
        //进行注册
        $userLogic = model('User','logic');
        $result = $userLogic->reg($paramAll);
        //$userLogic
        if($result === false){
            returnJson(['4020','注册失败',[]]);
        }

        returnJson($result);
    }

    /**
     * @api      {POST} /User/driverAuth  司机认证
     * @apiName  driverAuth
     * @apiGroup User
     * @apiHeader {String} authorization-token           token.
     * @apiParam {Number} logistics_type         物流类型 1：同城物流 2：长途物流
     * @apiParam {String} real_name          真实姓名.
     * @apiParam {String} sex        性别 1=男 2=女 0=未知.
     * @apiParam {String} identity         身份证号.
     * @apiParam {String} hold_pic         手持身份证照.
     * @apiParam {String} front_pic        身份证正面照.
     * @apiParam {String} back_pic         身份证反面照.
     *
     */
    public function driverAuth(){
        //校验参数
        $paramAll = $this->getReqParams(['logistics_type','real_name','sex', 'identity', 'hold_pic', 'front_pic','back_pic']);
        $rule = [
            'logistics_type' => ['require','regex'=>'/^(1|2)$/'],
            'real_name' => 'require|max:10',
            'sex' => ['require','regex'=>'/^(0|1|2)$/'],
            'identity' =>['require','regex'=>'/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/'],
            'hold_pic' =>'require',
            'front_pic' =>'require',
            'back_pic' =>'require',
        ];
        validateData($paramAll, $rule);
        //查看验证状态为init才可以进行验证
        $drBaseInfoLogic = model('DrBaseInfo','logic');
        $authStatus = $drBaseInfoLogic->getAuthStatus($this->loginUser['id']);
        if($authStatus != 'init'){
            $ret = [
                'code' => '4022',
                'msg' => '状态不合法',
                'result' => ['auth_status'=>$authStatus]
            ];
            returnJson($ret);
        }
        //验证信息入库更改状态为check

        $where = [
            'id' => $this->loginUser['id']
        ];
        $result = $drBaseInfoLogic->saveDriverAuth($where,$paramAll);
        returnJson($result);
    }

    /**
     * @api      {POST} /User/carAuth  车辆认证
     * @apiName  carAuth
     * @apiGroup User
     * @apiHeader {String} authorization-token           token.
     * @apiParam {String} car_type               车型.
     * @apiParam {String} car_style_type_id               车型ID.
     *
     * @apiParam {String} car_style_type_id               车长.
     *
     * @apiParam {String} length             车长.
     * @apiParam {String} card_number        车牌号.
     * @apiParam {String} policy_deadline    保单截止日期.
     * @apiParam {String} license_deadline   行驶证截止日期.
     * @apiParam {String} index_pic          车头和车牌号照片.
     * @apiParam {String} vehicle_license_pic 行驶证照片
     * @apiParam {String} driving_licence_pic 驾驶证照片
     * @apiParam {String} operation_pic         营运证照片
     */
    public function carAuth(){

        $paramAll['auth_status'] = 'check';//待认证
        //MapService::saveMapData($this->loginUser['user_name'],$paramAll['location']);
        dump($this->loginUser);die;//当前坐标.  104.394729,31.125698
    }
    /**
     * @api      {POST} /User/login 用户登录done
     * @apiName  login
     * @apiGroup User
     * @apiParam {String} account           账号/手机号/邮箱.
     * @apiParam {String} password          加密的密码. 加密方式：MD5("RUITU"+明文密码+"KEJI").
     * @apiParam {String} [wxOpenid]        微信openid.
     * @apiParam {String} pushToken         消息推送token.
     * @apiSuccess {String} accessToken     接口调用凭证.
     * @apiSuccess {String} refreshToken    刷新凭证.
     * @apiSuccess {Number} expireTime      有效期.
     * @apiSuccess {Number} userId          用户id.
     */
    public function login(Request $request){
        //校验参数
        $paramAll = $this->getReqParams(['account', 'password', 'wxOpenid', 'pushToken']);
        $rule = [
            'account' => 'require|max:32',
            'password' => 'require|length:6,128',
            'pushToken' => 'require|length:6,128',
        ];
        validateData($paramAll, $rule);
        $loginRet = \think\Loader::model('User', 'logic')->login($paramAll);
        returnJson($loginRet);
    }

    /**
     * @api      {POST} /User/forget   重置密码done
     * @apiName  resetPwd
     * @apiGroup User
     * @apiParam {String} account           账号/手机号/邮箱.
     * @apiParam {String} new_password          加密的密码. 加密方式：MD5("RUITU"+明文密码+"KEJI").
     * @apiParam {String} captcha           验证码.
     */
    public function forget(Request $request){
        //校验参数
        $paramAll = $this->getReqParams(['account', 'new_password', 'captcha']);
        $rule = [
            'account' => ['regex'=>'/^[1]{1}[3|5|7|8]{1}[0-9]{9}$/','require'],
            'new_password' => 'require|length:6,128',
            'captcha' => 'require|length:4,8',
        ];
        validateData($paramAll, $rule);
        //校验验证码
        $result = MsgService::verifyCaptcha($paramAll['account'],'resetpwd',$paramAll['captcha']);
        if($result['code'] != 2000){
            returnJson($result);
        }
        $userLogic = model('User','logic');

        $ret = $userLogic->resetPwd($paramAll['account'],$paramAll);
        returnJson($ret);
    }


    /**
     * @api      {GET} /user/info 获取用户信息(ok)
     * @apiName  info
     * @apiGroup User
     * @apiHeader {String} authorization-token           token.
     * @apiSuccess {Number} id                  id.
     * @apiSuccess {String} phone          绑定手机号.
     * @apiSuccess {Number} sex                 性别 1=男 2=女 0=未知.
     * @apiSuccess {String} avatar              头像.
     * @apiSuccess {String} real_name            昵称.
     * @apiSuccess {String} auth_status         认证状态（init=未认证， check=认证中，pass=认证通过，refuse=认证失败，delete=后台删除）
     */

    public function info(Request $request){
        $ret = model('User', 'logic')->getInfo($this->loginUser);
        returnJson($ret);
    }

    /**
     * @api      {POST} /user/uploadAvatar 上传并修改头像(ok)
     * @apiName  uploadAvatar
     * @apiGroup User
     * @apiHeader {String} authorization-token           token.
     * @apiParam {Image} file       上传的文件 最大5M 支持'jpg', 'gif', 'png', 'jpeg'
     * @apiParam {Number} [retType=json]   返回数据格式 默认=json  jsonp
     * @apiSuccess {String} url  下载链接(绝对路径)
     */
    public function uploadAvatar(){
        $file = $this->request->file('file');

        if(empty($file)){
            returnJson(4001);
        }
        $rule = ['size' => 1024*1024*5, 'ext' => 'jpg,gif,png,jpeg'];
        validateFile($file, $rule);
        $userLogic = model('User', 'logic');
        returnJson($userLogic->uploadAvatar($this->loginUser, $file));

    }

    /**
     * @api      {POST} /User/updatePwd   修改密码done
     * @apiName  updatePwd
     * @apiGroup User
     * @apiHeader {String}  authorization-token     token.
     * @apiParam {String} old_password      加密的密码. 加密方式：MD5("RUITU"+明文密码+"KEJI").
     * @apiParam {String} new_password      加密的密码. 加密方式：MD5("RUITU"+明文密码+"KEJI").
     * @apiParam {String} repeat_password   重复密码.
     */
    public function updatePwd(Request $request){
        //校验参数
        $paramAll = $this->getReqParams(['old_password', 'new_password', 'repeat_password']);
        $rule = [
            //'account' => ['regex'=>'/^[1]{1}[3|5|7|8]{1}[0-9]{9}$/','require'],
            'old_password' => 'require|length:6,128',
            'new_password' => 'require|length:6,128',
            'repeat_password' => 'require|confirm:new_password',
        ];

        validateData($paramAll, $rule);
        //校验验证码
        $userLogic = model('User','logic');
        $ret = $userLogic->resetPwd($this->loginUser,$paramAll);
        //$loginRet = \think\Loader::model('User', 'logic')->login($paramAll);
        returnJson($ret);
    }

    /**
     * @api      {POST} /User/isWork   工作状态
     * @apiName  isWork
     * @apiGroup User
     * @apiHeader {String} authorization-token           token.
     */
    public function isWork(){

    }

}