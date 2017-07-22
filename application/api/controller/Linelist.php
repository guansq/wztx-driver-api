<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/7
 * Time: 10:34
 */

namespace app\api\controller;

class Linelist extends BaseController {
    /**
     * @api     {POST}  /linelist/setline             设置路线done
     * @apiName setline
     * @apiGroup linelist
     * @apiHeader {String} authorization-token           token.
     * @apiParam   {String} org_city             路线的起始地省市区
     * @apiParam   {String} dest_city               路线的到达地省市区
     * @apiSuccess {String} drline_id               路线ID
     *
     */
    public function setline() {
        $paramAll = $this->getReqParams([
            'org_city',
            'dest_city',
        ]);
        $rule = [
            'org_city' => ['require'],
            'dest_city' => ['require'],
        ];
        validateData($paramAll, $rule);
        $paramAll['status'] = 0;
        $paramAll['dr_id'] = $this->loginUser['id'];
        //没有问题存入数据库
        $ret = model('Linelist', 'logic')->saveDrLinelist($paramAll);
        returnJson($ret);
    }

    /**
     * @api     {POST} /linelist/showline       获取路线信息done
     * @apiName    showline
     * @apiGroup    linelist
     * @apiSuccess  {Array}     list                            路线列表
     * @apiSuccess  {Number}    list.drline_id                  路线ID
     * @apiSuccess  {String}    list.org_city                   路线的起始地省市区
     * @apiSuccess  {String}    list.dest_city                  路线的到达地省市区
     */
    public function showline() {
        $ret = model('Linelist', 'logic')->getDrLineList(['dr_id' => $this->loginUser['id']]);
        if($ret['code'] != 2000){
            returnJson(4004, '未获取到路线信息');
        }
        returnJson($ret);
    }

    /**
     * @api     {POST} /linelist/delline      删除路线信息done
     * @apiName    delline
     * @apiGroup    linelist
     * @apiParam   {String} drline_id             路线ID
     */
    public function delline() {
        $paramAll = $this->getReqParams([
            'drline_id',
        ]);
        $rule = [
            'drline_id' => ['require', 'regex' => '^[0-9]*$'],
        ];
        validateData($paramAll, $rule);
        $ret = model('Linelist', 'logic')->delDrLineList(['dr_id' => $this->loginUser['id'], 'id' => $paramAll['drline_id']]);
        returnJson($ret);
    }
}