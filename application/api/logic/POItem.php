<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 9:45
 */

namespace app\api\logic;

class POItem extends BaseLogic{

    protected $table = 'atw_po_item';

    protected $STATUS_ARR = [
        'init' => '初始',

    ];

    /*
     * 得到所有的itemList
     */
    public function getAllItemList($where){
        if(!empty($where)){
            $list = $this->where($where)->group('sup_code')->select();
        }
        $list = $this->group('sup_code')->select();
        if($list){
            $list = collection($list)->toArray();
        }
        return $list;
    }

    /*
     * update
     */
    public function updatePoItem($where,$data){
        return $this->where($where)->update($data);
    }
}