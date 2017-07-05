<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 9:45
 */

namespace app\api\logic;

use service\HttpService;

class U9Api extends BaseLogic{


    public static function initU9Data(){

        $supplierLogic = Model('Supplier', 'logic');
        $ItemLogic = Model('Item', 'logic');
        $SupItemLogic = Model('SupItem', 'logic');
        $PRLogic = Model('PR', 'logic');
        $Iologic = Model('IO', 'logic');
        $poLogic = model('PO', 'logic');
        /** 同步ERP供应商信息到数据库*/
        $syncSupplierRet = $supplierLogic->initSupplier();

        /** 同步U9物料信息到数据库 */
        $syncItem = $ItemLogic->initItem();

        /** 同步U9物料-品牌交叉关系到数据库 */
        //$syncItemTrade = $ItemLogic->syncItemTrade();

        /** 同步U9 供应商-物料交叉 信息到数据*/
        $syncSupItemRet = $SupItemLogic->syncSupItem();

        /*** 同步U9 请购单 信息到数据库*/
        $syncPrRet = $PRLogic->initU9Data();

        /*** U9 请购单生产询价单*/
        $prToInquiryRet = $PRLogic->prToInquiry();

        /*** 评标*/
        //$evaluateBidRet = $Iologic->evaluateBid();

        /** 同步U9采购订单执行情况 */
        $syncU9StateRet = $poLogic->syncU9State();

        $ret = [
            'syncSupplierRet' => $syncSupplierRet,
            'syncItem' => $syncItem,
            //'syncItemTrade' => $syncItemTrade,
            'syncSupItemRet' => $syncSupItemRet,
            'syncPrRet' => $syncPrRet,
            'prToInquiryRet' => $prToInquiryRet,
            //'evaluateBidRet' => $evaluateBidRet,
            'syncU9StateRet' => $syncU9StateRet,
        ];
        trace($ret);
        return resultArray(2000, '', $ret);

    }

    public function syncAll(){

        $supplierLogic = Model('Supplier', 'logic');
        $ItemLogic = Model('Item', 'logic');
        $SupItemLogic = Model('SupItem', 'logic');
        $PRLogic = Model('PR', 'logic');
        $Iologic = Model('IO', 'logic');
        $poLogic = model('PO', 'logic');
        $QlfLogic = model('SupplierQualification', 'logic');

        /** 同步ERP供应商信息到数据库*/
        //$syncSupplierRet = $supplierLogic->syncSupplier();
        $syncSupplierRet = $supplierLogic->initSupplier();

        /** 同步U9物料信息到数据库 */
        $syncItem = $ItemLogic->syncItem();

        /** 同步U9物料-品牌交叉关系到数据库 */
        //$syncItemTrade = $ItemLogic->syncItemTrade();

        /** 同步U9 供应商-物料交叉 信息到数据*/
        $syncSupItemRet = $SupItemLogic->syncSupItem();

        /*** 同步U9 请购单 信息到数据库*/
        $syncPrRet = $PRLogic->syncU9Data();


        /*** U9 请购单生产询价单*/
        $prToInquiryRet = $PRLogic->prToInquiry();

        /*** 评标*/
        //$evaluateBidRet = $Iologic->evaluateBid();

        /** 同步U9采购订单执行情况 */
        $syncU9StateRet = $poLogic->syncU9State();

        //计算资质分
        $qlfScoreRet = $QlfLogic->computeQlfScore();

        // 供应商分数相关计算
        $computeScoreRet = $supplierLogic->computeScore();

        //统计过期数量
        $exceedQlfCount = $QlfLogic->countExceedQlf();
        $ret = [
            'syncSupplierRet' => $syncSupplierRet,
            'syncItem' => $syncItem,
            //'syncItemTrade' => $syncItemTrade,
            'syncSupItemRet' => $syncSupItemRet,
            'syncPrRet' => $syncPrRet,
            'prToInquiryRet' => $prToInquiryRet,
            //'evaluateBidRet' => $evaluateBidRet,
            'syncU9StateRet' => $syncU9StateRet,
            'qlfScoreRet' => $qlfScoreRet,
            'exceedQlfCount' => $exceedQlfCount,
            'computeScoreRet' => $computeScoreRet,
        ];
        trace($ret);
        return resultArray(2000, '', $ret);

    }

    public function httpGetSupplier($params = []){
        $httpRet = HttpService::get(getenv('APP_U9API_HOME').'/supplier', $params);
        if(empty($httpRet)){
            $this->resultSet['code'] = 4004;
            return $this->resultSet;
        }
        return json_decode($httpRet, true);
    }

    public function httpGetItem($params = []){
        $httpRet = HttpService::get(getenv('APP_U9API_HOME').'/item', $params);
        if(empty($httpRet)){
            $this->resultSet['code'] = 4004;
            return $this->resultSet;
        }
        return json_decode($httpRet, true);
    }

    public function httpGetItemTrade(){
        $httpRet = HttpService::get(getenv('APP_U9API_HOME').'/itemTrade');
        if(empty($httpRet)){
            $this->resultSet['code'] = 4004;
            return $this->resultSet;
        }
        return json_decode($httpRet, true);
    }

    public function httpGetSupItem(){
        $httpRet = HttpService::get(getenv('APP_U9API_HOME').'/supItem');
        if(empty($httpRet)){
            $this->resultSet['code'] = 4004;
            return $this->resultSet;
        }
        return json_decode($httpRet, true);
    }

    public function httpGetPr($params = []){
        if(empty($params)){
            $now = time();
            $params =['StartTime' => $now - 60*60*24, 'EndTime' => $now];
        }
        $httpRet = HttpService::get(getenv('APP_U9API_HOME').'/pr', $params);
        if(empty($httpRet)){
            $this->resultSet['code'] = 4004;
            return $this->resultSet;
        }
        return json_decode($httpRet, true);
    }

    public function httpCreatePO($po){
        $httpRet = HttpService::post(getenv('APP_U9API_HOME').'/po', $po);
        if(empty($httpRet)){
            $this->resultSet['code'] = 4004;
            return $this->resultSet;
        }
        return json_decode($httpRet, true);
    }

    public function httpGetPoStatus($po){
        $param = ['DocNo' => $po['order_code']];
        $httpRet = HttpService::get(getenv('APP_U9API_HOME').'/poState', $param);
        if(empty($httpRet)){
            $this->resultSet['code'] = 4004;
            return $this->resultSet;
        }
        return json_decode($httpRet, true);
    }


    public function httpGetPurchaseOrderPrice($supItem){
        $httpRet = HttpService::post(getenv('APP_U9API_HOME').'/purchaseOrderPrice', $supItem);
        if(empty($httpRet)){
            $this->resultSet['code'] = 4004;
            return $this->resultSet;
        }
        return json_decode($httpRet, true);
    }

}