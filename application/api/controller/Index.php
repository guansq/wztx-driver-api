<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/12
 * Time: 9:55
 */

namespace app\api\controller;

use service\MsgService;
use think\Request;

class Index extends BaseController{
    private $url = 'http://api.sendcloud.net/apiv2/mail/send';//普通发送
    private $templateurl = 'http://api.sendcloud.net/apiv2/mail/sendtemplate';


    /**
     * @api             {GET} /apiCode 返回码说明(ok)
     * @apiDescription  技术支持：<a href="http://www.ruitukeji.com" target="_blank">睿途科技</a>
     * @apiName         apiCode
     * @apiGroup        Index
     */
    public function apiCode(){
        //统计过期数量
//        $QlfLogic = model('SupplierQualification', 'logic');
//        $exceedQlfCount = $QlfLogic->countExceedQlf();
//        dd($exceedQlfCount);
        returnJson(2000, '技术支持：睿途科技(www.ruitukeji.com)', getCodeMsg());
    }

    /**
     * @api      {GET} /appConfig 应用配置参数done
     * @apiName  appConfig
     * @apiGroup Index
     * @apiSuccess {Array} payWays             付款方式 一维数组
     * @apiSuccess {String} xxx                其他参数
     */
    public function appConfig(){
        $ret= model('SystemConfig','logic')->getAppConfig();
        returnJson( $ret);
    }

    /**
     * @api      {GET} /lastApk 获取最新apk下载地址(ok)
     * @apiName  lastApk
     * @apiGroup Index
     * @apiSuccess {String} url                 下载链接.
     * @apiSuccess {Number} versionNum          真实版本号.
     * @apiSuccess {String} version             显示版本号.
     */
    public function lastApk(){
        $ret= model('SystemConfig','logic')->getLastApk();
        returnJson( $ret);
    }

    /**
     * @api      {GET} /index/home 首页(ok)
     * @apiDescription
     * @apiName  home
     * @apiGroup Index
     *
     * @apiSuccess {Array} banners        轮播图.
     * @apiSuccess {Number} banners.id      id.
     * @apiSuccess {Number} banners.seqNo   序号.
     * @apiSuccess {String} banners.link    跳转链接.
     * @apiSuccess {String} banners.img     图片.
     * @apiSuccess {String} [banners.title] 标题.
     */
    public function home(){
        $banners = model('SystemBanner', 'logic')->getBannerList();
        $unreadIoCnt = model('IO', 'logic')->countUnreadMsg($this->loginUser);
        $unreadPoCnt = model('PO', 'logic')->countUnreadMsg($this->loginUser);
        $unreadMsgCnt = model('Message', 'logic')->countUnreadMsg($this->loginUser);
        $unreadAskCnt = model('Ask', 'logic')->countUnreadMsg($this->loginUser);
        $ret = [
            'banners' => $banners,
            'unreadMsg' => [
                'io' => $unreadIoCnt,
                'po' => $unreadPoCnt,
                'msg' => $unreadMsgCnt,
                'ask' => $unreadAskCnt,
            ]
        ];
        returnJson(2000, '', $ret);
    }

    /**
     * @api      {POST} /index/sendCaptcha 发送验证码done
     * @apiName  sendCaptcha
     * @apiGroup Index
     * @apiParam {String} mobile   手机号.
     * @apiParam {String} opt      验证码类型 reg=注册 restpwd=找回密码 login=登陆 bind=绑定手机号.
     * @apiParam {String} codeId   此为客户端系统当前时间截 除去前两位后经MD5 加密后字符串.
     * @apiParam {String} validationId   codeIdvalidationId(此为手机号除去第一位后字符串+（codeId再次除去前三位） 生成字符串后经MD5加密后字符串)
     * 后端接收到此三个字符串后      也同样生成validationId
     * 与接收到的validationId进行对比 如果一致则发送短信验证码，否则不发送。同时建议对 codeId 进行唯一性检验   另外，错误时不要返回错误内容，只返回errCode，此设计仅限获取短信验证码
     */
    public function sendCaptcha(){
        $data = $this->getReqParams(['mobile', 'opt']);
        $rule = [
            'mobile' => 'require|min:7',
            'opt' => 'require'
        ];
        validateData($data, $rule);
        returnJson(MsgService::sendCaptcha($data['mobile'],$data['opt']));
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(){
        returnJson(2000, '显示创建资源表单页');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request){
        returnJson();
    }

    /**
     * 显示指定的资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function read($id){
        returnJson($id);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id){
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request $request
     * @param  int            $id
     * @return \think\Response
     */
    public function update(Request $request, $id){
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id){
        //
    }

    public function index(){
        echo 'test';
    }

}