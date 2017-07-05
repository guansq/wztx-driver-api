<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/4
 * Time: 10:15
 */

namespace app\api\logic;

use jwt\JwtHelper;

class User extends BaseLogic{

    protected $table = 'atw_system_user';

    /**
     * Author: WILL<314112362@qq.com>
     * Time: ${DAY}
     * Describe: 生成密码
     * @param $pWd  明文密码
     * @param $salt 盐值
     */
    private static function generatePwd($pwd, $salt){
        $encryptPwd = self::encryptPwd($pwd);
        return $pwd = self::encryptPwdSalt($encryptPwd, $salt);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Time: ${DAY}
     * Describe: 密码加密
     * @param $loginPWd
     * @param $salt
     */
    private static function encryptPwd($pwd){
        return $pwd = md5("RUITU{$pwd}KEJI");
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Time: ${DAY}
     * Describe: 密码加盐值加密
     * @param $loginPWd
     * @param $salt
     */
    private static function encryptPwdSalt($pwd, $salt = ''){
        return $pwd = sha1("THE{$salt}DAO{$pwd}");
    }


    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 用户登录逻辑
     * @param $params
     * @Param {String} account           账号/手机号/邮箱.
     * @Param {String} password          密码.
     * @Param {String} [wxOpenid]        微信openid.
     * @Param {String} [pushToken]       消息推送token.
     * @Success {String} accessToken     接口调用凭证.
     * @Success {String} refreshToken    刷新凭证.
     * @Success {Number} expireTime      有效期.
     */
    function login($params){
        $now = time();
        $account = $params['account'];
        $password = $params['password'];
        $wxOpenid = $params['wxOpenid'];
        $pushToken = $params['pushToken'];

        $loginUser = $this->findByAccount($account);

        if(empty($loginUser)){
            return resultArray(4014);
        }
        // 校验密码
        $ret = $this->checkPassword($loginUser, $password);
        if(!$ret){
            return resultArray(4014);
        }
        // 校验用户状态
        $ret = $this->checkStatus($loginUser);
        if(!$ret){
            return resultArray(4010, '用户已被锁定');
        }

        $loginUser->last_login_time = $now;
        $token = JwtHelper::encodeToken($loginUser);
        $ret = [
            'userId' => $loginUser->id,
            'accessToken' => $token,
            'refreshToken' => '没有哟~',
            'expireTime' => $loginUser->last_login_time + JwtHelper::DUE_TIME,
        ];
        //更新用户信息
        $updatedUser = [
            'wx_openid' => $wxOpenid,
            'push_token' => $pushToken,
            'last_login_time' => $now,
        ];
        $this->where(['push_token' => $pushToken])->update(['push_token' => null]);
        $this->where(['id' => $loginUser->id])->update($updatedUser);
        return resultArray(2000, '', $ret);

    }

    /**
     * Author: WILL<314112362@qq.com>
     * Time: 根据登录用户查询账号消息信息
     * Describe:
     * @param $loginUser
     *
     * @Success {Number} id                  id.
     * @Success {String} bindMobile          绑定手机号.
     * @Success {String} bindEmail           绑定邮箱.
     * @Success {Number} sex                 性别 1=男 2=女 0=未知.
     * @Success {String} avatar              头像.
     * @Success {String} nickName            昵称.
     * @Success {String} code                供应商编号.
     * @Success {String} name                供应商名称.
     * @Success {String} typeCode            主分类编码.
     * @Success {String} typeName            主分类名称.
     * @Success {String} taxCode             税号.
     * @Success {String} foundDate           成立日期.
     * @Success {String} taxRate             税率.
     * @Success {String} mobile              电话.
     * @Success {String} phone               手机.
     * @Success {String} email               邮箱.
     * @Success {String} fax                 传真.
     * @Success {String} ctcName             联系人.
     * @Success {String} address             地址.
     * @Success {String} payWay              付款方式.
     * @Success {String} comName             企业名称.
     * @Success {String} purchCode           采购员工号.
     * @Success {String} purchName           采购员工姓名.
     * @Success {String} purchType           供应商采购属性.
     * @Success {String} checkType           检验类型.
     * @Success {String} checkRate           抽检比例.
     * @Success {String} arvRate             到货率.
     * @Success {String} passRate            合格率.
     * @Success {String} creditLevel         信用等级.
     */
    public function getInfo($loginUser){
        $userFields = [
            'mobile' => 'bindMobile',
            'email' => 'bindEmail',
            'nick_name' => 'nickName',
            'avatar',
            'sex',
        ];
        $supFields = [
            'code',
            'name',
            'type_code' => 'typeCode',
            'type_name' => 'typeName',
            'state_tax_code' => 'taxCode',
            'found_date' => 'foundDate',
            'tax_rate' => 'taxRate',
            'mobile',
            'phone',
            'email',
            'fax',
            'ctc_name' => 'ctcName',
            'address',
            'pay_way' => 'payWay',
            'com_name' => 'comName',
            'purch_Code' => 'purchCode',
            'purch_name' => 'purchName',
            'purch_type' => 'purchType',
            'check_type' => 'checkType',
            'check_rate' => 'checkRate',
            'arv_rate' => 'arvRate',
            'pass_rate' => 'passRate',
        ];

        $userInfo = $this->field($userFields)->find($loginUser['id'])->toArray();
        $supInfo = model('SupplierInfo', 'logic')->findBySupId($loginUser['id'], $supFields)->toArray();

        $ret = array_merge($userInfo, $supInfo);
        $ret['passRate'] = empty($ret['passRate']) ? '0%' : rand($ret['passRate']*100, 0)."%";
        $ret['arvRate'] = empty($ret['arvRate']) ? '0%' : rand($ret['arvRate']*100, 0)."%";
        $ret['sex'] = empty($ret['sex']) ? 0 : $ret['sex'];
        $ret['foundDate'] = empty($ret['foundDate']) ? '' : date('Y-m-d', $ret['foundDate']);
        $ret['creditLevel'] = $this->getCreditLevel($supInfo);
        return resultArray(2000, '', $ret);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Time: 上传并更新头像
     * Describe:
     * @param $file
     */
    public function uploadAvatar($loginUser, $file){

        $fileLogic = model('File', 'logic');
        if(empty($_FILES)){
            return resultArray(4001);
        }
        $ossRet = $fileLogic->uploadFile($file);
        if(empty($ossRet) || $ossRet['code'] != 2000){
            return resultArray($ossRet);
        }
        $dbRet = $this->where('id', $loginUser['id'])->update(['avatar' => $ossRet['result']['file']['url']]);
        if(!$dbRet){
            return resultArray(5000);
        }
        return resultArray($ossRet);

    }

    /**
     * Author: WILL<314112362@qq.com>
     * Time: 更新用户信息
     * Describe:
     * @param $file
     */
    public function updateInfo($updateUser){
        $update = [];
        if($updateUser['sex']){
            $update['sex'] = $updateUser['sex'];
        }
        if($updateUser['avatar']){
            $update['avatar'] = $updateUser['avatar'];
        }
        if($updateUser['nickName']){
            $update['nick_name'] = $updateUser['nickName'];
        }
        $this->where('id',$updateUser['id'])->update($update);
        $update = [];
        if($updateUser['payWay']){
            $update['pay_way_change'] = $updateUser['payWay'];
            $update['pay_way_status'] = 'uncheck';
        }
        model('SupplierInfo', 'logic')->where('sup_id',$updateUser['id'])->update($update);
        return resultArray(2000);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Time: 根据登录名查询账号信息
     * Describe:
     * @param $account
     */
    private function findByAccount($account){
        return $this->where(['user_name' => $account])->find();
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 校验密码
     * @param $loginUser
     * @param $password
     */
    private function checkPassword($loginUser, $encryptPwd){
        $encryptPwd = self::encryptPwdSalt($encryptPwd, $loginUser->salt);
        return $loginUser->password === $encryptPwd;
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 校验用户状态
     * @param $loginUser
     */
    private function checkStatus($loginUser){
        return $loginUser->status == 'enabled';
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: TODO 计算用户信用等级
     * @param $supInfo
     */
    public function getCreditLevel($supInfo){
        return '优';
    }


}