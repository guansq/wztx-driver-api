<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 9:45
 */

namespace app\api\logic;

use think\Db;

class Message extends BaseLogic{

    const TYPE_ARR = [
        'single' => '私人消息',
        'multiple' => '群发消息',
        'all' => '系统消息',
        'tag' => '推送消息',
    ];

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 查询未读单数量 这只是私人消息的统计方式
     * @param $user
     */
    public function countUnreadMsg($user){
        return Db::table('atw_message_sendee')->where('read_at', 'NULL')->where('sendee_id', $user['id'])->count();
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 查询我的消息列表
     * @param $user
     *
     * @success {Array} list                 列表.
     * @success {Number} list.id              消息ID.
     * @success {String} list.type            类型.
     * @success {String} list.title           标题.
     * @success {String} list.summary         摘要.
     * @success {Number} list.isRead          是否阅读
     * @success {String} list.pushTime        推送时间.
     * @success {Number} page                页码.
     * @success {Number} pageSize            每页数据量.
     * @success {Number} dataTotal           数据总数.
     * @success {Number} pageTotal           总页码数.
     */
    public function getMyMessage($user, $pageParam){
        $MsgSendeeModel = db('MessageSendee');
        $list = [];
        $where = ['ms.sendee_id' => $user['id']];
        $fields = [
            'm.id' => 'id',
            'm.type' => 'type',
            'm.title' => 'title',
            'm.content' => 'content',
            'm.publish_time' => 'publish_time',
            'ms.read_at' => 'read_at',
        ];
        $dataTotal = $MsgSendeeModel->alias('ms')->where($where)->count();
        $dbRet = $this->alias('m')
            ->join('MessageSendee ms', 'm.id = ms.msg_id')
            ->where($where)
            ->page($pageParam['page'], $pageParam['pageSize'])
            ->field($fields)
            ->order('m.create_at DESC')
            ->select();
        if(empty($dataTotal)){
            return resultArray(4004);
        }

        foreach($dbRet as $item){
            $list[] = [
                'id' => $item['id'],
                'type' => $item['type'],
                'title' => $item['title'],
                'summary' => mb_substr($item['content'], 1, 20).'...',
                'isRead' => empty($item['read_at']) ? 0 : 1,
                'pushTime' => empty($item['publish_time']) ? '' : date('Y-m-d  H:i', $item['publish_time']),
            ];
        }

        $ret = [
            'list' => $list,
            'page' => $pageParam['page'],
            'pageSize' => $pageParam['pageSize'],
            'dataTotal' => $dataTotal,
            'pageTotal' => floor($dataTotal/$pageParam['pageSize']) + 1,
        ];

        return resultArray(2000, '', $ret);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 我的消息详情
     * @param $user
     * @success {Number} id              消息ID.
     * @success {String} type            类型.
     * @success {String} title           标题.
     * @success {String} content         内容.
     * @success {Number} isRead          是否阅读
     * @success {String} pushTime        推送时间.
     */
    public function getMyMsgDetail($id, $user){
        $where = [
            'ms.sendee_id' => $user['id'],
            'ms.msg_id' => $id
        ];
        $fields = [
            'm.id' => 'id',
            'm.type' => 'type',
            'm.title' => 'title',
            'm.content' => 'content',
            'm.publish_time' => 'publish_time',
            'ms.read_at' => 'read_at',
        ];
        $dbRet = $this->alias('m')->join('MessageSendee ms', 'm.id = ms.msg_id')->where($where)->field($fields)->find();
        if(empty($dbRet)){
            return resultArray(4004);
        }

        $ret = [
            'id' => $dbRet['id'],
            'type' => $dbRet['type'],
            'title' => $dbRet['title'],
            'content' => $dbRet['content'],
            'isRead' => empty($dbRet['read_at']) ? 0 : 1,
            'pushTime' => empty($dbRet['publish_time']) ? '' : date('Y-m-d H:i', $dbRet['publish_time']),
        ];
        if(empty($dbRet['read_at'])){
            db('MessageSendee')->alias('ms')->where($where)->update(['read_at' => time()]);
        }
        return resultArray(2000, '', $ret);
    }

}