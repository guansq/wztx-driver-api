<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/4
 * Time: 10:15
 */

namespace app\common\model;

use think\Model;

class BaseModel extends Model{

    public function save($data = [], $where = [], $sequence = null){
        if(!$this->isUpdate && !$where){
            $data['create_at'] = time();
        }
        $data['update_at'] = time();
        return parent::save($data, $where, $sequence);
    }

    public function insertGetId($data = [], $where = [], $sequence = null){
        if(!$this->isUpdate && !$where){
            $data['create_at'] = time();
        }
        $data['update_at'] = time();
        return parent::save($data, $where, $sequence);
    }

    public static function update($data = [], $where = [], $field = null){
        $data['update_at'] = time();
        return parent::update($data, $where, $field);
    }

    public static function create($data = [], $field = null){
        $now = time();
        $data['create_at'] = $now;
        $data['update_at'] = $now;
        return parent::create($data, $field);
    }

}