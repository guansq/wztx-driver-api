<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/12
 * Time: 9:55
 */

namespace app\api\controller;

use think\Request;

class User extends BaseController{


    /**
     * @api      {POST} /User/login 02.用户登录(ok)
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
     * @api      {POST} /User/resetPwd 03.重置密码(toto)
     * @apiName  resetPwd
     * @apiGroup User
     * @apiParam {String} account           账号/手机号/邮箱.
     * @apiParam {String} password          加密的密码. 加密方式：MD5("RUITU"+明文密码+"KEJI").
     * @apiParam {String} captcha           验证码.
     */
    public function resetPwd(Request $request){
        //校验参数
        $paramAll = $this->getReqParams(['account', 'password', 'captcha']);
        $rule = [
            'account' => 'require|max:32',
            'password' => 'require|length:6,128',
            'captcha' => 'require|length:4,8',
        ];
        validateData($paramAll, $rule);
        $loginRet = \think\Loader::model('User', 'logic')->login($paramAll);
        returnJson($loginRet);
    }


    /**
     * @api      {GET} /user/info 04.获取用户信息(ok)
     * @apiName  info
     * @apiGroup User
     * @apiHeader {String} authorization-token           token.
     * @apiSuccess {Number} id                  id.
     * @apiSuccess {String} bindMobile          绑定手机号.
     * @apiSuccess {String} bindEmail           绑定邮箱.
     * @apiSuccess {Number} sex                 性别 1=男 2=女 0=未知.
     * @apiSuccess {String} avatar              头像.
     * @apiSuccess {String} nickName            昵称.
     * @apiSuccess {String} code                供应商编号.
     * @apiSuccess {String} name                供应商名称.
     * @apiSuccess {String} typeCode            主分类编码.
     * @apiSuccess {String} typeName            主分类名称.
     * @apiSuccess {String} taxCode             税号.
     * @apiSuccess {String} foundDate           成立日期.
     * @apiSuccess {String} taxRate             税率.
     * @apiSuccess {String} mobile              电话.
     * @apiSuccess {String} phone               手机.
     * @apiSuccess {String} email               邮箱.
     * @apiSuccess {String} fax                 传真.
     * @apiSuccess {String} ctcName             联系人.
     * @apiSuccess {String} address             地址.
     * @apiSuccess {String} payWay              付款方式.
     * @apiSuccess {String} comName             企业名称.
     * @apiSuccess {String} purchCode           采购员工号.
     * @apiSuccess {String} purchName           采购员工姓名.
     * @apiSuccess {String} purchType           供应商采购属性.
     * @apiSuccess {String} checkType           检验类型.
     * @apiSuccess {String} checkRate           抽检比例.
     * @apiSuccess {String} arvRate             到货率.
     * @apiSuccess {String} passRate            合格率.
     * @apiSuccess {String} creditLevel         信用等级.
     */

    public function info(Request $request){
        $ret = model('User', 'logic')->getInfo($this->loginUser);
        returnJson($ret);
    }

    /**
     * @api      {POST} /user/uploadAvatar 05.上传并修改头像(ok)
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
     * @api      {PUT} /user/updateInfo 06.更新用户信息(ok)
     * @apiName  updateInfo
     * @apiGroup User
     * @apiHeader {String} authorization-token           token.
     * @apiParam {Number} [sex]                 性别 1=男 2=女 0=未知.
     * @apiParam {String} [avatar]              头像.
     * @apiParam {String} [nickName]            昵称.
     * @apiParam {String} [payWay]              付款方式.
     *//*
     * @apiParam {String} [mobile]              电话.
     * @apiParam {String} [phone]               手机.
     * @apiParam {String} [email]               邮箱.
     * @apiParam {String} [fax]                 传真.
     * @apiParam {String} [ctcName]             联系人.
     * @apiParam {String} [address]             地址.
     * @apiParam {String} [comName]             企业名称.
     */

    public function updateInfo(Request $request){
        //校验参数
        $paramAll = $this->getReqParams(['sex', 'avatar', 'nickName', 'payWay']);
        $rule = [
            'sex' => 'in:1,2',
            'avatar' => 'length:6,255',
            'nickName' => 'length:2,32',
            'payWay' => 'length:4,128',
        ];
        validateData($paramAll, $rule);
        $paramAll['id'] = $this->loginUser['id'];
        $loginRet = model('User', 'logic')->updateInfo($paramAll);
        returnJson($loginRet);
    }

    /**
     * @api      {GET} /User/qualification 06.获取用户资质 (ok)
     * @apiName  qualification
     * @apiGroup User
     * @apiHeader {String} authorization-token           token.
     * @apiSuccess {Array} list                  id.
     * @apiSuccess {Number} list.id                     id.
     * @apiSuccess {String} list.code                   资质编码 <br/>biz_lic=营业执照 <br/>tax_reg_ctf=税务登记证 <br/>org_code_ctf=组织机构代码证 <br/>prd_ctf=生产许可证 <br/>iso90001=ISO90001 <br/>ts_lic=TS认证 <br/>ped_lic=PED <br/>api_lic=API <br/>ce_lic=CE <br/>sil_lic=SIL <br/>bam_lic=BAM <br/>other=其他
     * @apiSuccess {String} list.name                   资质名称.
     * @apiSuccess {String} list.termStart              资质有效期起.
     * @apiSuccess {String} list.termEnd                资质有效期止.
     * @apiSuccess {String} list.status                 审核状态 ''=未审核 refuse=拒绝  agree=同意.
     * @apiSuccess {String} list.img                    图片地址.
     *
     */

    public function qualification(Request $request){

        $loginRet = model('SupplierQualification', 'logic')->getMyQualifications($this->loginUser);
        returnJson($loginRet);
    }

    /**
     * @api      {POST} /user/uploadQfctImg  07.上传资质证书(ok)
     * @apiName  uploadQfctImg
     * @apiGroup User
     * @apiHeader {String} authorization-token           token.
     * @apiParam {Image} file    上传的文件 最大5M 支持'jpg', 'gif', 'png', 'jpeg'
     * @apiSuccess {String} url  下载链接(绝对路径)
     */
    public function uploadQfctImg(){
        $file = $this->request->file('file');

        if(empty($file)){
            returnJson(4001);
        }
        $rule = ['size' => 1024*1024*5, 'ext' => 'jpg,gif,png,jpeg'];
        validateFile($file, $rule);
        $ogic = model('SupplierQualification', 'logic');
        returnJson($ogic->uploadQfctImg($file,$this->loginUser));
    }

    /**
     * @api      {POST} /user/qualification  07.保存资质证书信息(ok)
     * @apiName  saveQualification
     * @apiGroup User
     * @apiHeader {String} authorization-token           token.
     * @apiParam {String} list                       资质名称.
     * @apiParam {String} list.code                   资质编码 <br/>biz_lic=营业执照 <br/>tax_reg_ctf=税务登记证 <br/>org_code_ctf=组织机构代码证 <br/>prd_ctf=生产许可证 <br/>iso90001=ISO90001 <br/>ts_lic=TS认证 <br/>ped_lic=PED <br/>api_lic=API <br/>ce_lic=CE <br/>sil_lic=SIL <br/>bam_lic=BAM <br/>other=其他
     * @apiParam {String} list.name                   资质名称.
     * @apiParam {Number} list.termStart              资质有效期起.
     * @apiParam {Number} list.termEnd                资质有效期止.
     * @apiParam {String} list.img                    图片地址.
     */
    public function saveQualification(Request $request){
        $params =  $this->getReqParams(['list']);
        if(empty($params) || empty($params['list'])){
            returnJson(4001);
        }
        $qfctArr = $params['list'];
        $rule = [
            'code' => 'require|max:32',
            'name' => 'require|max:64',
            'termStart' => 'length:10,11',
            'termEnd' => 'length:10,11',
            'img' => 'max:255',
        ];
        foreach($qfctArr as $qfct){
            validateData($qfct,$rule);
        }
        $ogic = model('SupplierQualification', 'logic');
        returnJson($ogic->saveQualification($qfctArr,$this->loginUser));
    }

    /**
     * 计算资质分
     */
    public function computeQlfScore(Request $request){
        $logic = model('SupplierQualification', 'logic');
        returnJson($logic->computeQlfScore());
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(){
        returnJson();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(){
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request){
        //
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
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id){
        //
    }


}