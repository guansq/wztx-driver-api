<?php

use think\Route;


return [
    //-------------------
    //  __domain__  域名部署
    //-------------------
    '__domain__'=>[
        'drv.api.wztx' => 'api',
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
