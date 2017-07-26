<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/23
 * Time: 12:53
 */
namespace app\api\controller;
use think\Request;
use service\MapService;
class Goods extends BaseController{
    /**
     * @api {GET}   /goods/enableQuoteList     司机接单（可以报价）列表（有路线展示司机附近的货源信息，没有展示附近货源信息）done
     * @apiName     enableQuoteList
     * @apiGroup    Goods
     * @apiHeader   {String}    authorization-token     token.
     * @apiParam    {String}    [line_id]               路线ID
     * @apiSuccess  {String}    id  报价ID
     * @apiSuccess  {String}    org_city  起始地
     * @apiSuccess  {String}    dest_city 目的地
     * @apiSuccess  {String}    goods_name      货品名称
     * @apiSuccess  {String}    weight          货品重量
     * @apiSuccess  {String}    system_price    系统出价
     * @apiSuccess  {String}    sp_price        货主出价
     * @apiSuccess  {String}    usecar_time     用车时间
     */
    public function enableQuoteList(){
        //判断是否上下班时间
        //dump($this->loginUser);die;
        if($this->loginUser['online'] == 1){
            returnJson(4000,'您现在是下班状态不能接单');
        }
        $pathInfo = model('Linelist','logic')->getDrLineList(['dr_id'=>$this->loginUser['id']]);
        $paramAll = $this->getReqParams(['line_id']);
        $pageParam = $this->getPagingParams();
        if($pathInfo['code'] == 4000){
            //没有路线的司机
            $map_code = $this->loginUser['map_code'];
            if(empty($map_code)){
                returnJson(4000,'司机地图ID为空');
            }
            $mapInfo = MapService::getCurLocal($map_code);
            //$mapInfo[0]
            $curMapInfo = explode(',',$mapInfo[0]['_location']);
            $curLongitude = $curMapInfo[0];
            $curLatitude = $curMapInfo[1];
            $result = model('Goods','logic')->findGoodsList($curLongitude,$curLatitude,$pageParam);
        }else{
            //有路线的司机返回最新的司机
            if(isset($paramAll['line_id']) && !empty($paramAll['line_id'])){
                $info = model('Linelist','logic')->getLineInfo(['dr_id'=>$this->loginUser['id'],'id'=>$paramAll['line_id'],'status'=>0]);
                if($info['code'] == 4000){
                    returnJson($info);
                }
                $info = $info['result'];
                $where['org_city'] = ['like',"%{$info['org_city']}%"];
                $where['dest_city'] = ['like',"%{$info['dest_city']}%"];
            }else{
                //dump($pathInfo);die;
                $info = $pathInfo['result']['list'][0];
                $where['org_city'] = ['like',"%{$info['org_city']}%"];
                $where['dest_city'] = ['like',"%{$info['dest_city']}%"];
            }
            $where['status'] = 'quote';//待报价
            $result = model('Goods','logic')->getGoodsList($where,$pageParam);
        }
        returnJson($result);
    }

    /**
     * @api     {GET}       /goods/goodsList        货源列表（根据设定路线展示）done
     * @apiName goodsList
     * @apiGroup Goods
     * @apiParam   {String} [org_city]        出发地
     * @apiParam   {String} [dest_city]     目的地
     * @apiParam   {Number} [car_style_length_id]     车长ID
     * @apiParam   {Number} [car_style_type_id]     车型ID
     * @apiSuccess  {Array} list            列表
     * @apiSuccess  {String} list.id       订单ID
     * @apiSuccess  {String} list.org_city       出发地
     * @apiSuccess  {String} list.dest_city      目的地
     * @apiSuccess  {String} list.mind_price     心理价格
     * @apiSuccess  {String} list.system_price   系统价格
     * @apiSuccess  {String} list.goods_name     货物名称
     * @apiSuccess  {String} list.weight         总重量（吨）
     */
    public function goodsList(){
        $paramAll = $this->getReqParams(['org_city','dest_city','car_style_length_id','car_style_type_id']);
        $pageParam =$this->getPagingParams();
        $where = [];
        if(isset($paramAll['org_city'])&& !empty($paramAll['org_city'])){
            $where['org_city'] = ['like',"%{$paramAll['org_city']}%"];
        }
        if(isset($paramAll['dest_city'])&& !empty($paramAll['dest_city'])){
            $where['dest_city'] = ['like',"%{$paramAll['dest_city']}%"];
        }
        if(isset($paramAll['car_style_length_id'])&& !empty($paramAll['car_style_length_id'])){
            $where['car_style_length_id'] = ['like',"%{$paramAll['car_style_length_id']}%"];
        }
        if(isset($paramAll['car_style_type_id'])&& !empty($paramAll['car_style_type_id'])){
            $where['car_style_type_id'] = ['like',"%{$paramAll['car_style_type_id']}%"];
        }
        $where['status'] = 'quote';//待报价
        $ret = model('Goods','logic')->getGoodsList($where,$pageParam);
        returnJson($ret);
    }

    /**
     * @api     {GET}  /goods/detail            货源详情done
     * @apiName detail
     * @apiGroup Goods
     * @apiHeader {String} authorization-token           token.
     * @apiParam    {Int}    id                 货源ID
     * @apiSuccess  {String} goods_name         货品名称
     * @apiSuccess  {String} weight             重量
     * @apiSuccess  {String} org_city           起始地
     * @apiSuccess  {String} dest_city          目的地
     * @apiSuccess  {String} dest_receive_name  收货人姓名
     * @apiSuccess  {String} dest_phone         收货人电话
     * @apiSuccess  {String} dest_address_name  收货人地址
     * @apiSuccess  {String} dest_address_detail收货人地址详情
     * @apiSuccess  {String} org_send_name      寄件人姓名
     * @apiSuccess  {String} org_phone          寄件人电话
     * @apiSuccess  {String} org_address_name   寄件人地址
     * @apiSuccess  {String} org_address_datail 寄件人地址详情
     * @apiSuccess  {String} usecar_time        用车时间
     * @apiSuccess  {String} send_time          发货时间
     * @apiSuccess  {String} arr_time           到达时间
     * @apiSuccess  {String} real_name          车主姓名
     * @apiSuccess  {String} phone              联系电话
     * @apiSuccess  {String} policy_code        保单编号
     * @apiSuccess  {Int} is_pay                是否支付1为已支付 0为未支付
     * @apiSuccess  {String} is_receipt         货物回单1-是-默认，2-否
     * @apiSuccess  {String} system_price       系统出价
     * @apiSuccess  {String} mind_price         货主出价
     * @apiSuccess  {String} final_price        总运费
     */
    public function detail() {
        $paramAll = $this->getReqParams([
            'id',
        ]);
        $rule = [
            'id' => ['require', 'regex' => '^[0-9]*$'],
        ];

        validateData($paramAll, $rule);
        //'dr_id' => $this->loginUser['id'],
        $goodsInfo = model('Goods', 'logic')->getGoodsInfo([ 'id' => $paramAll['id']]);
        if (empty($goodsInfo)) {
            returnJson('4004', '未获取到货源信息');
        }
        if ($this->loginUser['id'] == $goodsInfo['dr_id']) {
            $drBaseInfo = model('DrBaseInfo', 'logic')->findInfoByUserId($this->loginUser['id']);
            $dr_phone = $drBaseInfo['phone'];
            $dr_real_name = $drBaseInfo['real_name'];
        } else {
            $dr_phone = '';
            $dr_real_name = '';
        }

        $detail = [
            'status' => $goodsInfo['status'],
            'goods_name' => $goodsInfo['goods_name'],
            'weight' => strval($goodsInfo['weight']),
            'org_city' => $goodsInfo['org_city'],
            'dest_city' => $goodsInfo['dest_city'],
            'dest_receive_name' => $goodsInfo['dest_receive_name'],
            'dest_phone' => $goodsInfo['dest_phone'],
            'dest_address_name' => $goodsInfo['dest_address_name'],
            'dest_address_detail' => $goodsInfo['dest_address_detail'],
            'org_send_name' => $goodsInfo['org_send_name'],
            'org_phone' => $goodsInfo['org_phone'],
            'org_address_name' => $goodsInfo['org_address_name'],
            'org_address_detail' => $goodsInfo['org_address_detail'],
            'usecar_time' => wztxDate($goodsInfo['usecar_time']),
            'real_name' => $dr_real_name,
            'phone' => $dr_phone,
            'policy_code' => $goodsInfo['policy_code'],
            'is_pay' => $goodsInfo['is_pay'],
            'is_receipt' => $goodsInfo['is_receipt'],
            'system_price' => wztxMoney($goodsInfo['system_price']),
            'mind_price' => wztxMoney($goodsInfo['mind_price']),
            'final_price' => wztxMoney($goodsInfo['final_price']),
        ];
        returnJson('2000', '成功', $detail);
    }

}