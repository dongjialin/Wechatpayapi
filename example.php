<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2019/1/12
 * Time: 12:19 PM
 */
require_once('./Wechatpay.php');

$mp = array(
    'appid'=>'微信appid',
    'mch_id'=>'商户号',
    'key'=>'key',
);
$w = new Wechatpay($mp);

$data = array(
    'body' => '商品描述',
    'out_trade_no' => '订单号',
    'total_fee' => '金额（分）',
    'notify_url' => '回调地址',
);
$res = $w->wechatAppPay($data);
echo json_encode($res);