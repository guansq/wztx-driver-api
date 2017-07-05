<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 9:45
 */

namespace app\api\logic;

use think\Db;
use think\Exception;

class Ask extends BaseLogic{

    protected $table = 'atw_ask_reply';

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 查询未读单数量
     * @param $user
     */
    public function countUnreadMsg($user){
        return Db::table('atw_ask_reply')->where('read_at', 'NULL')->where('sender_id', $user['id'])->count();
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Time: ${DAY}
     * Describe: 我的咨询列表
     * @param $user
     * @param $pagingParam
     *
     * @success {Array} list                      咨询列表.
     * @success {Number} list.id                  咨询单id.
     * @success {String} list.content             咨询内容.
     * @success {String} list.reply               最后一条回复内容.
     * @success {String} list.replyTime           最后一条回复时间.
     * @success {String} list.replierId           最后一条回复人id.
     * @success {String} list.replierName         最后一条回复人名称.
     * @success {String} list.isRead              是否阅读.
     */
    public function getMyList($user, $pageParam){
        $where = [
            'type' => 'ask',
            'sender_id' => $user['id'],
        ];
        $dataTotal = $this->where($where)->count();
        if(empty($dataTotal)){
            return resultArray(4004);
        }
        $dbRet = $this->where($where)
            ->order('create_at DESC')
            ->page($pageParam['page'], $pageParam['pageSize'])
            ->select();
        $dbList = [];
        foreach($dbRet as $ask){
            $lastReply = $this->getLastReply($ask);
            $dbList[] = [
                'id' => $ask['id'],
                'content' => $ask['content'],
                'reply' => $lastReply['content'],
                'replyTime' => empty($lastReply['create_at']) ? '' : date('Y-m-d', $lastReply['create_at']),
                'replierId' => $lastReply['sender_id'],
                'replierName' => $lastReply['sender_name'],
                'isRead' => empty($ask['read_at']) ? 0 : 1,
            ];
        }
        $pageData = [
            'list' => $dbList,
            'page' => $pageParam['page'],
            'pageSize' => $pageParam['pageSize'],
            'dataTotal' => $dataTotal,
            'pageTotal' => floor($dataTotal/$pageParam['pageSize']) + 1,
        ];

        return resultArray(2000, '', $pageData);

    }

    /**
     * Author: WILL<314112362@qq.com>
     * Time: ${DAY}
     * Describe: 我的咨询详情
     * @param $user
     * @param $pagingParam
     *
     * @success {Number} list.id                  回复id.
     * @success {String} list.content             回复内容.
     * @success {String} list.time                回复时间.
     * @success {String} list.replierId           回复人id.
     * @success {String} list.replierName         回复人名称.
     * @success {String} list.replierAvatar       回复人头像.
     * @success {String} list.isRead              是否阅读.
     */
    public function getAskList($id, $user, $pageParam){
        $where = [
            'id|pid' => $id,
            'sender_id|sendee_id' => $user['id'],
        ];
        $dataTotal = $this->where($where)->count();
        //dd($this->getLastSql());
        if(empty($dataTotal)){
            return resultArray(4004);
        }
        $dbRet = $this->where($where)
            ->order('create_at DESC')
            ->page($pageParam['page'], $pageParam['pageSize'])
            ->select();
        //记录阅读状态
        $this->isUpdate(true)->where($where)->where('read_at', 'NULL')->update(['read_at' => time()]);

        $dbList = [];
        foreach($dbRet as $ask){
            $sender = $this->getSender($ask);
            $dbList[] = [
                'id' => $ask['id'],
                'content' => $ask['content'],
                'time' => empty($ask['create_at']) ? '' : date('Y-m-d', $ask['create_at']),
                'replierId' => $ask['sender_id'],
                'replierName' => $sender['name'],
                'replierAvatar' => $sender['avatar'],
                'isRead' => empty($ask['read_at']) ? 0 : 1,
            ];
        }
        $pageData = [
            'list' => $dbList,
            'page' => $pageParam['page'],
            'pageSize' => $pageParam['pageSize'],
            'dataTotal' => $dataTotal,
            'pageTotal' => floor($dataTotal/$pageParam['pageSize']) + 1,
        ];

        return resultArray(2000, '', $pageData);

    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 获取最后一条回复消息
     * @param $ask
     */
    public function getLastReply($ask){
        $replyDefault = [
            'id' => 0,
            'content' => '',
            'type' => '',
            'pid' => 0,
            'sender_id' => 0,
            'sender_name' => '',
            'sendee_id' => 0,
            'read_at' => 0,
            'create_at' => 0,
            'update_at' => 0,
            'delete_at' => 0,
        ];
        $reply = $this->where('type', 'reply')->where('pid', $ask['id'])->order('create_at', 'DESC')->find();
        if(empty($reply)){
            return $replyDefault;
        }
        $sup = model('SupplierInfo', 'logic')->find($ask['sender_id']);
        $reply['sender_name'] = $sup['name'];
        return $reply;
    }


    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 添加新的问题
     * @param $user
     * @param $content
     */
    public function addAsk($user, $content){
        $saveData = [
            'content' => $content,
            'type' => 'ask',
            'pid' => 0,
            'sender_id' => $user['id'],
            'sendee_id' => 0,
        ];
        try{
            $this->data($saveData)->save();
        }catch(Exception $e){
            return resultArray(5020, '', $e);
        }
        return resultArray(2000);
    }

    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 回复问题
     * @param $user
     * @param $content
     */
    public function replyAsk($ask, $user, $content){
        $saveData = [
            'content' => $content,
            'type' => 'reply',
            'pid' => $ask['id'],
            'sender_id' => $user['id'],
            'sendee_id' => 0,
        ];
        try{
            $this->data($saveData)->save();
        }catch(Exception $e){
            return resultArray(5020, '', $e);
        }
        return resultArray(2000);
    }


    /**
     * Author: WILL<314112362@qq.com>
     * Describe: 获取发送人信息
     * @param $ask
     */
    private function getSender($ask){
        $sup = model('SupplierInfo', 'logic')
            ->alias('sup')
            ->join('SystemUser u', 'sup.sup_id=u.id')
            ->where('sup.sup_id', $ask['sender_id'])
            ->find();
        if(empty($sup)){
            $sup = [
                'id' => $ask['sender_id'],
                'name' => '',
                'avatar' => '',
            ];
        }
        return $sup;
    }

}