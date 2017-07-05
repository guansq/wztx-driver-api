<?php
/**
 * 采购单
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/12
 * Time: 9:55
 */

namespace app\api\controller;

use think\Request;

class Message extends BaseController{



    /**
     * @api      {GET} /message 01.我的消息-列表(ok)
     * @apiName  index
     * @apiGroup Message
     * @apiHeader {String} authorization-token   token.
     *
     * @apiParam {String} [type=private]           消息类型. all=全部  system=系统消息 private=私人消息
     * @apiParam {Number} [page=1]                  页码.
     * @apiParam {Number} [pageSize=20]             每页数据量.
     *
     * @apiSuccess {Array} list                 列表.
     * @apiSuccess {Number} list.id              消息ID.
     * @apiSuccess {String} list.type            类型.
     * @apiSuccess {String} list.title           标题.
     * @apiSuccess {String} list.summary         摘要.
     * @apiSuccess {Number} list.isRead          是否阅读
     * @apiSuccess {String} list.pushTime        推送时间.
     * @apiSuccess {Number} page                页码.
     * @apiSuccess {Number} pageSize            每页数据量.
     * @apiSuccess {Number} dataTotal           数据总数.
     * @apiSuccess {Number} pageTotal           总页码数.
     */
    public function index(){
        $pageParam = $this->getPagingParams();
        $ret =  model('Message','logic')->getMyMessage($this->loginUser,$pageParam);
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
     * 保存新建的资源
     *
     * @param  \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request){
        //
    }

    /**
     * @api {GET} /message/:id  02.我的消息-详情(ok)
     * @apiName read
     * @apiGroup Message
     * @apiHeader {String} authorization-token   token.
     * @apiParam {Number} id          id.
     * @apiSuccess {Number} id              消息ID.
     * @apiSuccess {String} type            类型.
     * @apiSuccess {String} title           标题.
     * @apiSuccess {String} content         内容.
     * @apiSuccess {Number} isRead          是否阅读
     * @apiSuccess {String} pushTime        推送时间.
     */
    public function read($id){
        $ret = model('Message','logic')->getMyMsgDetail($id,$this->loginUser);
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
        //
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