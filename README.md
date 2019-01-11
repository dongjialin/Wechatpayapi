# 微信支付简单集成
```
$mp = array(
    'appid'=>'',//微信appid
    'mch_id'=>'',//商户号
    'key'=>'',//key
);
$w = new Wechatpay($mp);

$data = array(
    'body' => '商品描述',
    'out_trade_no' => '订单号',
    'total_fee' => '金额（分）',
    'notify_url' => '回调地址',
);
$res = $w->wechatAppPay($data);
```
- wechatAppPay APP支付
- wechatXiaoPay 小程序支付
- jsapipay JSAPI支付
- htmlpay H5支付

```
//查询订单
$data = array(
    'out_trade_no' => '订单号',
);
$res = $w->wechatOrderresult($data);
```

- 持续更新中。。。
