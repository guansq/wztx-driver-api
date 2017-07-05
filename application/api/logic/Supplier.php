<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 9:45
 */

namespace app\api\logic;


class Supplier extends BaseLogic{


    public function initSupplier(){
        $params = ['StartTime' => 1];
        $u9Ret = Model('U9Api', 'logic')->httpGetSupplier($params);
        if($u9Ret['code'] != 2000){
            return resultArray($u9Ret);
        }
        $u9List = $u9Ret['result']['list'];
        if(empty($u9List)){
            return resultArray(4004);
        }
        return $this->insertSupplier($u9List);
    }


    /**
     * Author: WILL<314112362@qq.com>
     * Describe:把U9数据同步到数据库
     */
    function syncSupplier(){
        $params = ['StartTime' => time() - 60*60*24];
        $u9Ret = Model('U9Api', 'logic')->httpGetSupplier($params);
        if($u9Ret['code'] != 2000){
            return resultArray($u9Ret);
        }
        $u9List = $u9Ret['result']['list'];
        if(empty($u9List)){
            return resultArray(4004);
        }
        return $this->insertSupplier($u9List);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe:把U9数据插入到数据库
     */
    function insertSupplier($u9List){
        $addTendencyCnt = $updatedCount = $createdCount = 0;
        foreach($u9List as $k => $v){
            if(!is_numeric($k)){
                $v = $u9List;
            }

            $saveDate = [
                'code' => $v['SupCode'],
                'name' => $v['SupName'],
                'type_code' => $v['SupTypeCode'],
                'type_name' => $v['SupTypeName'],
                'state_tax_code' => $v['StateTaxNo'],
                'national_tax_code' => $v['DistrictTaxNo'],
                'found_date' => $v['EstDate'],
                'tax_rate' => $v['TaxRate'],
                'mobile' => $v['PerMoblieNum'],
                'phone' => $v['PerPhoneNum'],
                'email' => $v['Email'],
                'fax' => $v['FaxNum'],
                'ctc_name' => $v['PerName'],
                'address' => $v['Address'],
                'pay_way' => $v['PayType'],
                'com_name' => $v['SupName'],
                'purch_code' => $v['PurPersonCode'],
                'purch_name' => $v['PurPersonName'],
                'purch_type' => $v['SupCode'],
                'arv_rate' => $v['PurTimelyArrivalRate']/100,
                'pass_rate' => $v['PurArrPassPercent']/100,
                'biz_score' => 0,
                'qlf_score' => 0,
                'tech_score' => ($v['PurTimelyArrivalRate']*0.2 + $v['PurArrPassPercent']*0.6)*0.4,  // 技术分的 b+c 部分,
                //'check_type' => $v['SupCode'],
                //'check_rate' => $v['SupCode'],
            ];
            // $u9SupModel = new  U9Supplier();
            // if(U9Supplier::exist($saveDate)){
            //     //trace('$u9SupModel isUpdate ===================true');
            //     $u9SupModel->isUpdate(true)->save($saveDate,[ 'code' => $saveDate['code']]);
            // }else{
            //     //trace('$u9SupModel isUpdate ===================false');
            //     $u9SupModel->isUpdate(false)->data($saveDate,true)->save();
            // }
            $tendency = [
                'sup_code' => $v['SupCode'],
                'sup_name' => $v['SupName'],
                'arv_rate' => $v['PurTimelyArrivalRate']/100,
                'pass_rate' => $v['PurArrPassPercent']/100,
                'sync_date' => time(),
            ];
            $addTendencyCnt += model('SupplierTendency', 'logic')->create($tendency)?1:0;

            $SupInfoModel = new  SupplierInfo();
            //是否存在
            if(SupplierInfo::exist($saveDate)){
                //trace('$u9SupModel isUpdate ===================true');
                $updatedCount += $SupInfoModel->isUpdate(true)->save($saveDate, ['code' => $saveDate['code']]);
            }else{
                //trace('$u9SupModel isUpdate ===================false');
                $createdCount += $SupInfoModel->isUpdate(false)->data($saveDate, true)->save();
            }


            if(!is_numeric($k)){
                break;
            }
        }

        $ret = [
            'addTendencyCnt' => $addTendencyCnt,
            'updatedCount' => $updatedCount,
            'createdCount' => $createdCount
        ];
        return resultArray(2000, null, $ret);
    }


    /*
     * 得到U9供应商数据
     */
    public function getListInfo(){
        $list = supModel::alias('a')
            ->field('a.id,a.code,a.name,a.type_code,a.type_name,a.status,t.arv_rate,t.pp_rate')
            ->join('supplier_tendency t', 'a.code=t.sup_code', 'LEFT')
            ->select();

        if($list){
            $list = collection($list)->toArray();
        }
        //dump($list);die;
        return $list;
    }

    /**
     * 判断U9的数据是否存在
     */
    public function exist($data){
        $count = self::field('code')->where('code', $data['code'])->count();
        return $count == 0 ? true : false;//不存在true 存在false
    }

    /**
     * 保存U9到数据库
     */
    public function saveData($data){
        return model('SupplierInfo')->saveData($data);
    }

    /**
     * 保存批量数据到数据库
     */
    public function saveAllData($data){
        return model('SupplierInfo')->saveAllData($data);
    }

    /**
     * 得到单个供应商信息
     */
    public function getOneSupInfo($sup_id){
        //缺少建立日期,技术分,责任采购,信用等级,供应风险
        $supinfo = supModel::alias('a')
            ->field('a.id,a.name,a.code,u.user_name,a.type_code,a.type_name,a.tax_code,a.found_date,a.ctc_name,a.mobile,a.fax,a.email,a.address,a.status,a.purch_type,a.check_type,a.check_rate,t.arv_rate,t.pp_rate')
            ->join('supplier_tendency t', 'a.code=t.sup_code', 'LEFT')
            ->join('system_user u', 'a.sup_id=u.id', 'LEFT')
            ->where('a.id', $sup_id)
            ->find();
        if($supinfo){
            $supinfo = $supinfo->toArray();
        }
        return $supinfo;
    }

    /**
     * 得到供应商图片信息
     */
    public function getSupQuali($sup_code){
        return $supQuali = model('SupplierQualification')->where("sup_code", $sup_code)->select();
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Time: ${DAY}
     * Describe:供应商分数相关计算
     */
    public function computeScore(){
        $supList = model('SupplierInfo','logic')->where('1=1')->select();
        if(empty($supList)){
            return resultArray(4004);
        }
        $count =0;
        foreach($supList as $sup){
            $adjustScore = 15-5* $sup->readjust_count;
            $adjustScore = $adjustScore < 0?0:$adjustScore;
            $giveupScore = $sup->giveup_count>0?0:25;
            $sup->credit_total = $sup->pass_rate*30+$sup->arv_rate*30+$adjustScore+$giveupScore;
            $count+=$sup->save();
        }
        return $count;
    }
}