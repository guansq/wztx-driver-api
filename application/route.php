<?php

use think\Route;
Route::any('apiCode','Index/apiCode');
Route::any('appConfig','Index/appConfig');
Route::any('lastApk','Index/lastApk');
Route::get('index/home','Index/home');
Route::get('index/getAdvertisement','Index/getAdvertisement');
Route::post('index/sendCaptcha','Index/sendCaptcha');
Route::get('index/getArticle','Index/getArticle');
Route::get('u9api/syncAll','U9Api/syncAll');
Route::get('u9api/initU9Data','U9Api/initU9Data');
Route::get('u9api/initSupplier','U9Api/initSupplier');
Route::get('u9api/syncSupplier','U9Api/syncSupplier');
Route::get('u9api/syncItem','U9Api/syncItem');
Route::get('u9api/initItem','U9Api/initItem');
Route::get('u9api/syncItemTrade','U9Api/syncItemTrade');
Route::get('u9api/syncSupItem','U9Api/syncSupItem');
Route::get('u9api/syncPr','U9Api/syncPr');
Route::get('u9api/initPr','U9Api/initPr');
Route::get('u9api/prToInquiry','U9Api/prToInquiry');
Route::get('u9api/evaluateBid','U9Api/evaluateBid');
Route::get('u9api/syncPOState','U9Api/syncPOState');
Route::get('u9api/placeOrder','U9Api/placeOrder');

Route::get('user/info','User/info');
Route::get('user/computeQlfScore','User/computeQlfScore');
Route::post('user/login','User/login');
Route::post('user/uploadAvatar','User/uploadAvatar');
Route::put('user/updateInfo','User/updateInfo');
Route::get('user/qualification','User/qualification');
Route::post('user/qualification','User/saveQualification');
Route::post('user/uploadQfctImg','User/uploadQfctImg');

Route::post('ask/reply','Ask/reply');

Route::post('inquiry/quote','inquiry/quote');

Route::put('po/agree','PO/agree');
Route::put('po/refuse','PO/refuse');

return [
    //-------------------
    //  __domain__  域名部署
    //-------------------
    '__domain__'=>[
        'wztx.drv.api' => 'api',
    ],
    '__rest__' => [
        'index' => 'Index',             // 路径 =》 控制器
        'supporter' => 'Supporter',
        'inquiry' => 'Inquiry',
        'po' => 'PO',
//        'message' => 'Message',
        'ask' => 'Ask',
    ],
];
