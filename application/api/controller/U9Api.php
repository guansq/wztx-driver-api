<?php
/**
 * 供应商
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/12
 * Time: 9:55
 */

namespace app\api\controller;


class U9Api extends BaseController{

    /**
     * 同步ERP所有信息到数据库
     */
    public function initU9Data(){
        $logic = Model('U9Api', 'logic');
        return returnJson($logic->initU9Data());
    }
    /**
     * 同步ERP所有信息到数据库
     */
    public function syncAll(){
        $logic = Model('U9Api', 'logic');
        return returnJson($logic->syncAll());
    }

    /**
     * 同步ERP供应商信息到数据库
     */
    public function syncSupplier(){
        $supplierLogic = Model('Supplier', 'logic');
        return returnJson($supplierLogic->syncSupplier());
    }

    /**
     * 同步ERP供应商信息到数据库
     */
    public function initSupplier(){
        $supplierLogic = Model('Supplier', 'logic');
        return returnJson($supplierLogic->initSupplier());
    }

    /**
     * 同步U9物料信息到数据库
     */
    public function initItem(){
        $logic = Model('Item', 'logic');
        return returnJson($logic->initItem());
    }
    /**
     * 同步U9物料信息到数据库
     */
    public function syncItem(){
        $logic = Model('Item', 'logic');
        return returnJson($logic->syncItem());
    }

    /**
     * 同步U9 品牌-物料交叉 信息到数据库
     */
    public function syncItemTrade(){
        $logic = Model('Item', 'logic');
        return returnJson($logic->syncItemTrade());
    }

    /**
     * 同步U9 供应商-物料交叉 信息到数据库
     */
    public function syncSupItem(){
        $logic = Model('SupItem', 'logic');
        return returnJson($logic->syncSupItem());
    }

    /**
     * 同步U9 请购单 信息到数据库
     */
    public function syncPr(){
        $logic = Model('PR', 'logic');
        return returnJson($logic->syncU9Data());
    }

    /**
     * 初始化U9 请购单 信息到数据库
     */
    public function initPr(){
        $logic = Model('PR', 'logic');
        return returnJson($logic->initU9Data());
    }


    /**
     * U9 请购单生产询价单
     */
    public function prToInquiry(){
        $logic = Model('PR', 'logic');
        return returnJson($logic->prToInquiry());
    }

    /**
     * 评标
     */
    public function evaluateBid(){
        $logic = Model('IO', 'logic');
        return returnJson($logic->evaluateBid());
    }

    /**
     * 同步U9采购订单执行情况(ok)
     *
     * Param {String} [code]            订单编号.
     *
     */
    public function syncPOState(){
        $reqParam = $this->getReqParams(['code' => '']);
        $poLogic = model('PO', 'logic');
        returnJson($poLogic->syncU9State($reqParam));
    }

    /**
     * 自动下单
     */
    public function placeOrder(){
        $poLogic = model('PO', 'logic');
        $POItemLogic = model('POItem', 'logic');
        //得到全部init的订单
        $where = ['status'=>'init'];
        $list = $POItemLogic->getAllItemList($where);
        //dump($list);
        foreach($list as $k=>$v){
            $poData = [
                'pr_code' => $v['pr_code'],
                'sup_code' => $v['sup_code'],
                'is_include_tax' => 1,      //是否含税
                'status' => 'init',
                'create_at' => time(),
                'update_at' => time(),
            ];
            $po_id = $poLogic->insertGetId($poData);
            $where = [
                'sup_code' => $v['sup_code']
            ];
            $data = [
                'po_id' => $po_id,
                'status' => 'placeorder'
            ];
            $POItemLogic->updatePoItem($where,$data);
        }
        returnJson('2000','自动下单成功',[]);
    }
}