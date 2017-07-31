<?php

// +----------------------------------------------------------------------
// | Think.Admin
// +----------------------------------------------------------------------
// | 版权所有 2014~2017 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://think.ctolog.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/Think.Admin
// +----------------------------------------------------------------------

namespace app\api\logic;

use \think\Db;
use app\common\model\SystemArea as SystemAreaModel;

/**
 * 系统配置
 * Class Node
 * @package app\admin\model
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/03/14 18:12
 */
class SystemConfig extends BaseLogic {

    public function getAppConfig() {
        $config = [
            'lastApkUrl' => getSysconf('last_apk_url', ''),
            'lastApkVersion' => getSysconf('last_apk_version', ''),
            'lastApkVersionNum' => intval(getSysconf('last_apk_version_num', '')),
            'defaultAvatar' => 'http://opmnz562z.bkt.clouddn.com/b22e33502ca2e6e1d93a996e062e8f2d.png',
            'share_percent' => getSysconf('share_percent'),
            'grab_range' => getSysconf('grab_range'),
            'premium_rate' => getSysconf('premium_rate'),
            'bond_person_amount' => getSysconf('bond_person_amount'),
            'bond_company_amount' => getSysconf('bond_company_amount'),
            //'' => getSysconf(''),
            'withdraw_begintime' => getSysconf('withdraw_begintime'),
            'withdraw_endtime' => getSysconf('withdraw_endtime'),
            'custom_phone' => getSysconf('custom_phone'),
            'custom_email' => getSysconf('custom_email'),
            'complain_phone' => getSysconf('complain_phone'),
            'weixin_limit' => getSysconf('weixin_limit'),
            'alipay_limit' => getSysconf('alipay_limit'),
            'tran_account' => getSysconf('tran_account'),
            'share_shipper' => getSysconf('share_shipper'),
            'share_driver' => getSysconf('share_driver'),
            'xx' => '其他参数',
        ];

        return resultArray(2000, '', $config);
    }

    public function getLastApk() {

        $lastApk = [
            'url' => getSysconf('last_apk_url', ''),
            'version' => getSysconf('last_apk_version', ''),
            'versionNum' => getSysconf('last_apk_version_num', ''),
        ];

        return resultArray(2000, '', $lastApk);
    }

}
