<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2019/1/12
 * Time: 12:19 PM
 */

use Wechatpayapi\Wechatpay;

require_once('./src/Wechatpayapi/Wechatpay.php');
$mp = array(
    'appid' => 'wxa7b508bd77bd92e8',//微信appid
    'mch_id' => '1511118601',//商户号
    'key' => 'A118BE3740B7ECAC60D25B0D4AE2C480',//key
);
$w = new Wechatpay($mp);

$data = array(
    'body' => '测试',//商品描述
    'out_trade_no' => '10000000',//订单号
    'total_fee' => '100',//金额（分）
    'notify_url' => 'http://www.google.com',//回调地址
);
$res = $w->wechatAppPay($data);
echo json_encode($res);
