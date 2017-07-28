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
        $where = [
            'read_at' => 'NULL',
            'type' => 1
        ];
        return Db::table('rt_message_sendee')->where($where)->where('sendee_id', $user['id'])->count();
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
        if ($user['push_type'] == 'private') {
            if (empty($user['id'])) {
                return resultArray(4004);
            }
            $list = [];
            $where = [
                'ms.sendee_id' => $user['id'],
                'ms.type' => 1,
                'm.push_type' => ['not in', ['all']]
            ];
            $dataTotal = $this->alias('m')
                ->join('MessageSendee ms', 'm.id = ms.msg_id')
                ->where($where)
                ->count();
            if (empty($dataTotal)) {
                return resultArray(4004);
            }
            $dbRet = $this->alias('m')
                ->join('MessageSendee ms', 'm.id = ms.msg_id')
                ->where($where)
                ->page($pageParam['page'], $pageParam['pageSize'])
                ->field("m.*,ms.read_at")
                ->order('m.create_at DESC')
                ->select();
            $unreadnum = 0;
            foreach ($dbRet as $item) {
                $isRead = empty($item['read_at']) ? 0 : 1;
                if(empty($isRead)){
                    $unreadnum += 1;
                }
                $list[] = [
                    'id' => $item['id'],
                    'type' => $item['type'],
                    'push_type' => $item['push_type'],
                    'title' => $item['title'],
                    'summary' => mb_substr($item['content'], 1, 20) . '...',
                    'isRead' => $isRead,
                    'pushTime' => empty($item['publish_time']) ? '' : date('Y-m-d  H:i', $item['publish_time']),
                ];
            }
            $ret = [
                'list' => $list,
                'page' => $pageParam['page'],
                'pageSize' => $pageParam['pageSize'],
                'dataTotal' => $dataTotal,
                'pageTotal' => intval(($dataTotal + $pageParam['pageSize'] - 1) / $pageParam['pageSize']),
                'unreadnum'=>$unreadnum
            ];
            return resultArray(2000, '', $ret);
        }
        if ($user['push_type'] == 'system') {
            $list = [];
            $where = [
                'ms.type' => 1,
                'ms.push_type' => 'all'
            ];
            $dataTotal = $this->alias('ms')->where($where)->count();
            if (empty($dataTotal)) {
                return resultArray(4004);
            }
            $dbRet = $this->alias('ms')
                ->where($where)
                ->page($pageParam['page'], $pageParam['pageSize'])
                ->field("ms.*")
                ->order('ms.create_at DESC')
                ->select();
            $unreadnum = 0;
            foreach ($dbRet as $item) {
                if (empty($user['id'])) {
                    $isRead = 0;
                    $unreadnum = $unreadnum+1;
                } else {
                    $MsgSendeeModel = db('MessageSendee');
                    $info = $MsgSendeeModel->alias('m')->where(['sendee_id' => $user['id'], 'msg_id' => $item['id'], 'type' => 1])->find();
                    if (empty($info) || empty($info['read_at'])) {
                        $isRead = 0;
                        $unreadnum = $unreadnum+1;
                    } else {
                        $isRead = 1;
                    }
                }
                $list[] = [
                    'id' => $item['id'],
                    'type' => $item['type'],
                    'push_type' => $item['push_type'],
                    'title' => $item['title'],
                    'summary' => mb_substr($item['content'], 1, 20) . '...',
                    'isRead' => $isRead,
                    'pushTime' => empty($item['publish_time']) ? '' : date('Y-m-d  H:i', $item['publish_time']),
                ];
            }

            $ret = [
                'list' => $list,
                'page' => $pageParam['page'],
                'pageSize' => $pageParam['pageSize'],
                'dataTotal' => $dataTotal,
                'pageTotal' => intval(($dataTotal + $pageParam['pageSize'] - 1) / $pageParam['pageSize']),
                'unreadnum'=>$unreadnum
            ];

            return resultArray(2000, '', $ret);
        }
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
        $detailMsg = $this->where(['id' => $id,'type'=>1])->find();
        if (empty($detailMsg)) {
            return resultArray(4004);
        }
        if ($detailMsg['push_type'] != 'all') {
            if (empty($user['id'])) {
                return resultArray(4004);
            }
            $where = [
                'ms.sendee_id' => $user['id'],
                'ms.msg_id' => $id,
                'ms.type' => 1
            ];
            $dbRet = $this->alias('m')->join('MessageSendee ms', 'm.id = ms.msg_id')->where($where)->field("m.*,ms.read_at")->find();
            if (empty($dbRet)) {
                return resultArray(4004);
            }
            $ret = [
                'id' => $dbRet['id'],
                'type' => $dbRet['type'],
                'title' => $dbRet['title'],
                'push_type' => $dbRet['push_type'],
                'content' => $dbRet['content'],
                'isRead' => empty($dbRet['read_at']) ? 0 : 1,
                'pushTime' => empty($dbRet['publish_time']) ? '' : date('Y-m-d H:i', $dbRet['publish_time']),
            ];
            if (empty($dbRet['read_at'])) {
                db('MessageSendee')->alias('ms')->where($where)->update(['read_at' => time()]);
            }
            return resultArray(2000, '', $ret);

        } else {
            if (!empty($user['id'])) {
                $MsgSendeeModel = db('MessageSendee');
                $info = $MsgSendeeModel->alias('m')->where(['sendee_id' => $user['id'], 'msg_id' =>$detailMsg['id'], 'type' => 1])->find();
                if(empty($info)){
                    //插入阅读数据
                    $insertData['msg_id'] = $detailMsg['id'];
                    $insertData['sendee_id'] = $user['id'];
                    $insertData['create_at'] = time();
                    $insertData['read_at'] =time();
                    $insertData['type'] = 1;
                    db('MessageSendee')->insert($insertData);
                }
            }
            $ret = [
                'id' => $detailMsg['id'],
                'type' => $detailMsg['type'],
                'push_type' => $detailMsg['push_type'],
                'title' => $detailMsg['title'],
                'content' => $detailMsg['content'],
                'isRead' => 1,
                'pushTime' => empty($detailMsg['publish_time']) ? '' : date('Y-m-d H:i', $detailMsg['publish_time']),
            ];
            return resultArray(2000, '', $ret);
        }
    }

}