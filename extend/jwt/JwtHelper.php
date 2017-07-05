<?php
/**
 * User: Plator
 * Date: 2017/5/19
 * Time: 9:26
 * Desc: JWT加密使用
 */

namespace jwt;

use Firebase\JWT\JWT;
use think\Db;

class JwtHelper{
    const JWT_KEY         = "JWT_KEY_ATW";
    const JWT_SIGN_KEY    = "SIGN_KEY_ATW";
    const DUE_TIME        = 60*60*24*365;  //token 有效期
    const USER_TABLE_NAME = 'atw_system_user';  //用户表名

    public static function encodeToken($loginUser){

        $data = [
            'last_login_time' => $loginUser->last_login_time,
            'password' => $loginUser->password,
            'salt' => $loginUser->salt,
            'user_id' => $loginUser->id
        ];

        $sign = self::tokenSign($data);
        $expire_time = $data["last_login_time"] + self::DUE_TIME;
        //dd(date('Y-m-d H:i:s',$expire_time));
        $token = array(
            "data" => [$data["user_id"], $sign],
            "exp" => $expire_time,
        );
        return JWT::encode($token, self::JWT_KEY);
    }

    public static function decodeToken($jwt){
        try{
            return (Array)JWT::decode($jwt, self::JWT_KEY, ['HS256']);
        }catch(\Exception $e){
            returnJson(4012, $e->getMessage());
        }
    }

    public static function checkToken($jwt){
        $decode = self::decodeToken($jwt);
        $user_id = $decode["data"][0];
        $sign = $decode["data"][1];
        $userInfo = Db::table(self::USER_TABLE_NAME)->where(['id' => $user_id])->find();

        if(empty($userInfo)){
            returnJson(4012);
        }
        if(empty($sign) || $sign != self::tokenSign($userInfo)){
            returnJson(4015);
        }
        return $userInfo;
    }


    /**
     * token签名
     * @param $userInfo
     * @return bool|string
     */
    static function tokenSign($userInfo){
        $pre_sign = [
            "publish" => self::JWT_SIGN_KEY,
            "password" => md5($userInfo["password"]),
            "salt" => $userInfo["salt"],
            "last_login_time" => $userInfo["last_login_time"]
        ];
        trace('token签名===============================>');
        trace($pre_sign);
        ksort($pre_sign);
        $sign_build = http_build_query($pre_sign);
        return substr(base64_encode(hash_hmac('sha256', $sign_build, self::JWT_SIGN_KEY, true)), 2, 10);
    }
}

