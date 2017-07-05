<?php
/**
 * 咨询
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/12
 * Time: 9:55
 */

namespace app\api\controller;

use think\Request;

class Ask extends BaseController{


    /**
     * @api      {GET} /ask 01.咨询列表(ok)
     * @apiName  index
     * @apiGroup Ask
     * @apiHeader {String} authorization-token           token.
     *
     * @apiSuccess {Array} list                      咨询列表.
     * @apiSuccess {Number} list.id                  咨询单id.
     * @apiSuccess {String} list.content             咨询内容.
     * @apiSuccess {String} list.reply               最后一条回复内容.
     * @apiSuccess {String} list.replyTime           最后一条回复时间.
     * @apiSuccess {String} list.replierId           最后一条回复人id.
     * @apiSuccess {String} list.replierName         最后一条回复人名称.
     * @apiSuccess {String} list.isRead              是否阅读.
     *
     */
    public function index(Request $request){
        $pagingParams = $this->getPagingParams();
        $ret = model('Ask', 'logic')->getMyList($this->loginUser, $pagingParams);
        returnJson($ret);
    }


    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(){
        //
    }

    /**
     * @api      {POST} /ask 03.新增咨询(ok)
     * @apiName  save
     * @apiGroup Ask
     * @apiHeader {String} authorization-token   token.
     *
     * @apiParam {String} content             咨询内容.
     *
     */
    public function save(Request $request){
        $params = $this->getReqParams(['content']);
        $rule = [
            'content' => 'require|max:250',
        ];
        validateData($params, $rule);
        $ret = model('Ask', 'logic')->addAsk($this->loginUser, $params['content']);
        returnJson($ret);
    }

    /**
     * @api      {GET} /ask/:id 04.咨询的回复记录(ok)
     * @apiName  read
     * @apiGroup Ask
     * @apiHeader {String} authorization-token   token.
     * @apiParam  {Number} id             咨询id.
     * @apiSuccess {Array} list                      回复列表.
     * @apiSuccess {Number} list.id                  回复id.
     * @apiSuccess {String} list.content             回复内容.
     * @apiSuccess {String} list.time                回复时间.
     * @apiSuccess {String} list.replierId           回复人id.
     * @apiSuccess {String} list.replierName         回复人名称.
     * @apiSuccess {String} list.replierAvatar       回复人头像.
     * @apiSuccess {String} list.isRead              是否阅读.
     *
     */
    public function read($id){
        $pagingParams = $this->getPagingParams();
        $ret = model('Ask', 'logic')->getAskList($id, $this->loginUser, $pagingParams);
        returnJson($ret);
    }

    /**
     * @api      {POST} /ask/reply 05.咨询发送消息(ok)
     * @apiName  reply
     * @apiGroup Ask
     * @apiHeader {String} authorization-token   token.
     * @apiParam  {Number} id             咨询id.
     * @apiParam  {String} content        回复内容.
     *
     */
    public function reply(Request $request){
        $params = $this->getReqParams(['id', 'content']);
        $rule = [
            'id' => 'require',
            'content' => 'require|max:250',
        ];
        validateData($params, $rule);
        $ask = model('Ask', 'logic')->find($params['id']);
        if(empty($ask)){
            returnJson(4004);
        }
        $ret = model('Ask', 'logic')->replyAsk($ask, $this->loginUser, $params['content']);
        returnJson($ret);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id){
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request $request
     * @param  int            $id
     * @return \think\Response
     */
    public function update(Request $request, $id){

    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id){
        //
    }


}