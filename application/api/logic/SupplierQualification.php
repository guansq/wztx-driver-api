<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/4
 * Time: 10:15
 */

namespace app\api\logic;


class SupplierQualification extends BaseLogic{

    const CODE_ARR = [
        'biz_lic' => [
            'name' => '营业执照',
            'img' => "http://opmnz562z.bkt.clouddn.com/c5f32ca95f05108f4d37d4e34fd0e0f6.png"
        ],
        'tax_reg_ctf' => [
            'name' => '税务登记证',
            'img' => 'http://opmnz562z.bkt.clouddn.com/4abdf952137101ddfeffc709d72b5a2f.png'
        ],
        'org_code_ctf' => [
            'name' => '组织机构代码证',
            'img' => 'http://opmnz562z.bkt.clouddn.com/01cce7155ec94cd560d7d673b6588c5d.png'
        ],
        //'prd_ctf' => '生产许可证',
        'iso90001' => [
            'name' => 'ISO90001',
            'img' => 'http://opmnz562z.bkt.clouddn.com/f82786ed8a79fc07d49e9e14412b013e.png'
        ],
        'ts_lic' => [
            'name' => 'TS认证',
            'img' => 'http://opmnz562z.bkt.clouddn.com/bd7744b62a912c34f27d2db0a4871a2e.png'
        ],
        'ped_lic' => [
            'name' => 'PED',
            'img' => 'http://opmnz562z.bkt.clouddn.com/5781cbc795b64a9c6a1dbfd08ad50bbd.png'
        ],
        'api_lic' => [
            'name' => 'API',
            'img' => "http://opmnz562z.bkt.clouddn.com/5851682b4d668b75dd267ba3a00b798c.png"
        ],
        'ce_lic' => [
            'name' => 'CE',
            'img' => 'http://opmnz562z.bkt.clouddn.com/b297484d130bec4da42b390fb5a62ccf.png'
        ],
        'sil_lic' => [
            'name' => 'SIL',
            'img' => 'http://opmnz562z.bkt.clouddn.com/a6bea58fc57a2c1fe05c262385fad703.png'
        ],
        'bam_lic' => [
            'name' => 'BAM',
            'img' => 'http://opmnz562z.bkt.clouddn.com/695ce2e5b972782964b7124fb0977a47.png'
        ],
        'other' => [
            'name' => '其他',
            'img' => 'http://opmnz562z.bkt.clouddn.com/e4cf4ef488c76324c575cbe1c44af532.png'
        ],
    ];
    /**
     * ''=未审核 refuse=拒绝  agree=同意.
     */
    const STATUS_ARR = [
        '' => '未审核',
        'refuse' => '拒绝',
        'agree' => '同意',
    ];

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 获取用户的资质列表
     * @param $user
     * Success {Number} list.id                     id.
     * Success {String} list.code                   资质编码 <br/>biz_lic=营业执照 <br/>tax_reg_ctf=税务登记证 <br/>org_code_ctf=组织机构代码证 <br/>prd_ctf=生产许可证 <br/>iso90001=ISO90001 <br/>ts_lic=TS认证 <br/>ped_lic=PED <br/>api_lic=API <br/>ce_lic=CE <br/>sil_lic=SIL <br/>bam_lic=BAM <br/>other=其他
     * Success {String} list.name                   资质名称.
     * Success {String} list.termStart              资质有效期起.
     * Success {String} list.termEnd                资质有效期止.
     * Success {String} list.status                 审核状态 init=未审核 refuse=拒绝  agree=同意.
     * Success {String} list.img                    图片地址.
     */
    public function getMyQualifications($user){
        $ret = [];
        foreach(self::CODE_ARR as $code => $value){

            $dbQua = $this->where('code', $code)->where('sup_code', $user['sup_code'])->find();
            $item = [
                'id' => 0,
                'code' => $code,
                'name' => $value['name'],
                'termStart' => '',
                'termEnd' => '',
                'status' => '',
                'img' => $value['img'],
            ];

            if(empty($dbQua)){
                $ret[] = $item;
                continue;
            }

            $ret[] = [
                'id' => $dbQua['id'],
                'code' => $dbQua['code'],
                'name' => $dbQua['name'],
                'termStart' => $dbQua['term_start'],
                //'termStart' =>empty( $dbQua['term_start']) ? '' : date('Y-m-d', $dbQua['term_start']),
                'termEnd' => $dbQua['term_end'],
                //'termEnd' =>empty( $dbQua['term_end'] )? '' : date('Y-m-d', $dbQua['term_end']),
                'status' => empty($dbQua['status']) ? '未审核' : self::STATUS_ARR[$dbQua['status']],
                'img' => $dbQua['img_src'],
            ];
        }

        return resultArray(2000, '', ['list' => $ret]);

    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 上传资质证书
     * @param \think\File $file
     * @param array       $user
     * @return array
     */
    public function uploadQfctImg(\think\File $file, $user = []){
        $fileLogic = model('File', 'logic');
        if(empty($file)){
            return resultArray(4001);
        }
        $ossRet = $fileLogic->uploadFile($file);
        if(empty($ossRet) || $ossRet['code'] != 2000){
            return resultArray($ossRet);
        }
        return resultArray($ossRet);

    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 保存资质证书
     * @param array $params
     * @param array $user
     * @return array
     */
    public function saveQualification($params, $user){
        $countSave = 0;
        $countUpdate = 0;
        foreach($params as $item){

            if(empty($item['termStart']) || empty($item['termEnd']) || empty($item['img']) || empty($item['code'])){
                continue;
            }
            $data = [
                'code' => $item['code'],
                'name' => $item['name'],
                'term_start' => $item['termStart'],
                'term_end' => $item['termEnd'],
                'img_src' => $item['img'],
                'sup_code' => $user['sup_code'],
                'com_name' => $user['sup_name'],
                'status' => '',
            ];
            $queryData = $this->where('code', $data['code'])->where('sup_code', $data['sup_code'])->find();
            if(empty($queryData)){
                $countSave += $this->isUpdate(false)->data($data)->save();
                continue;
            }
            if($queryData->term_start == $data['term_start'] && $queryData->term_end == $data['term_end'] && $queryData->img_src == $data['img_src']){
                continue;
            }
            $countUpdate += $this->isUpdate(true)
                ->where('code', $data['code'])
                ->where('sup_code', $data['sup_code'])
                ->update($data);
        }

        return resultArray(2000, '', ['countSave' => $countSave, 'countUpdate' => $countUpdate]);

    }

    /**
     * Author: WILL<314112362@qq.com>
     * Time: ${DAY}
     * Describe:
     * 计算资质分 认证资质（ISO 5分；TS 5分；API 5分；PED 5分；）
     */
    public function computeQlfScore(){
        $supLogic = model('SupplierInfo', 'logic');
        $zeroCnt = $supLogic->where('1=1')->update(['qlf_score' => 0]);
        $qlfList = $this->where('code', 'IN', ['iso90001', 'ts_lic', 'api_lic', 'ped_lic'])
            ->where('term_end', '>', time())
            ->where('status', 'agree')
            ->select();
        $qlfCnt = count($qlfList);
        if($qlfCnt==0){
            return resultArray(4004);
        }
        foreach($qlfList as $qlf){
            $supLogic->where('code',$qlf['sup_code'])->setInc('qlf_score',5);
            $supLogic->where('code',$qlf['sup_code'])->setInc('tech_score',5*0.4);
        }

        return resultArray(2000,'',[
            'zeroCnt'=>$zeroCnt,
            'qlfCnt'=>$qlfCnt,
        ]);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Time: ${DAY}
     * Describe:
     * 统计过期数量
     */
    public function countExceedQlf(){
        $count = 0;
        $now = time();
        $supLogic = model('SupplierInfo', 'logic');
        $codeArr = $supLogic->where('1=1')->column('code');
        if(empty($codeArr)){
            return $count;
        }
        foreach($codeArr as $supCode){
            $cnt = $this->where('sup_code', $supCode)->where('term_end', '<=', $now)->count();
            $supLogic->where('code',$supCode)->update(['qlf_exceed_count'=>$cnt]);
            $count+=$cnt;
        }
        return $count;
    }

}