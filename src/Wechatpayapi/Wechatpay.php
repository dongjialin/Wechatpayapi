<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2019/1/16
 * Time: 2:50 PM
 */

namespace Wechatpayapi;

class Wechatpay
{
   private $appid;
   private $mch_id;
   private $key;

   /**
    * 初始化参数
    *
    * @param array $options
    * @param $options ['appid']
    * @param $options ['mch_id']
    * @param $options ['key']
    */
   public function __construct($options)
   {
       $this->appid = isset($options['appid']) ? $options['appid'] : '';
       $this->mch_id = isset($options['mch_id']) ? $options['mch_id'] : '';
       $this->key = isset($options['key']) ? $options['key'] : '';
   }

    /**
     * APP统一下单
     *
     * @param array $options
     */
    public function wechatAppPay($options)
    {
        $options['appid'] = $this->appid;
        $options['mch_id'] = $this->mch_id;
        $options['spbill_create_ip'] = $this->get_client_ip(); //终端IP
        $options['trade_type'] = "APP"; //交易类型
        $options['nonce_str'] = strtoupper(md5(time()));
        ksort($options);
        //签名
        $stringA = $this->arr2str($options);
        $stringSignTemp = $stringA . "key=" . $this->key;
        $options['sign'] = strtoupper(md5($stringSignTemp));

        $postData = $this->buildXml($options);
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

        $dataxml = $this->postXmlCurl($url, $postData);
        if ($dataxml['return_code'] == "SUCCESS") {
            if ($dataxml['result_code'] == "SUCCESS") {
                $new['partnerid'] = $dataxml['mch_id'];
                $new['appid'] = $dataxml['appid'];
                $new['package'] = "Sign=WXPay";
                $new['timestamp'] = (string)time();
                $new['prepayid'] = $dataxml['prepay_id'];
                $new['noncestr'] = $dataxml['nonce_str'];
                ksort($new);
                $str = $this->arr2str($new);
                $new['sign'] = md5($str . "key=" . $this->key);

                $data = array(
                    'code' => 200,
                    'content' => $new,
                );
                return $data;
            } else {
                return $dataxml;
            }
        } else {
            return $dataxml;
        }
    }

    /**
     * 小程序统一下单
     *
     * @param array $options
     */
    public function wechatXiaoiPay($options)
    {
        $options['appid'] = $this->appid;
        $options['mch_id'] = $this->mch_id;
        $options['spbill_create_ip'] = $this->get_client_ip(); //终端IP
        $options['trade_type'] = "JSAPI"; //交易类型
        $options['nonce_str'] = strtoupper(md5(time()));
        ksort($options);
        //签名
        $stringA = $this->arr2str($options);
        $stringSignTemp = $stringA . "key=" . $this->key;
        $options['sign'] = strtoupper(md5($stringSignTemp));

        $postData = $this->buildXml($options);
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

        $dataxml = $this->postXmlCurl($url, $postData);
        if ($dataxml['return_code'] == "SUCCESS") {
            if ($dataxml['result_code'] == "SUCCESS") {
                $new['appId'] = $dataxml['appid'];//小程序ID
                $new['package'] = "prepay_id=" . $dataxml['prepay_id'];//数据包
                $new['timeStamp'] = (string)time();
                $new['nonceStr'] = $dataxml['nonce_str'];
                $new['signType'] = "MD5";
                ksort($new);
                $str = $this->arr2str($new);
                $new['paySign'] = md5($str . "key=" . $this->key);
                $new['order_id'] = $options['out_trade_no'];

                $data = array(
                    'code' => 200,
                    'content' => $new,
                );
                return $data;
            } else {
                return $dataxml;
            }
        } else {
            return $dataxml;
        }
    }


    /**
     * JSAPI统一下单
     *
     * @param array $options
     */
    public function jsapipay($options)
    {
        $options['appid'] = $this->appid;
        $options['mch_id'] = $this->mch_id;
        $options['spbill_create_ip'] = $this->get_client_ip(); //终端IP
        $options['trade_type'] = "JSAPI"; //交易类型
        $options['nonce_str'] = strtoupper(md5(time()));
        ksort($options);
        //签名
        $stringA = $this->arr2str($options);
        $stringSignTemp = $stringA . "key=" . $this->key;
        $options['sign'] = strtoupper(md5($stringSignTemp));

        $postData = $this->buildXml($options);
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

        $dataxml = $this->postXmlCurl($url, $postData);
        return $dataxml;
    }


    /**
     * H5统一下单
     *
     * @param array $options
     */
    public function htmlpay($options)
    {
        $options['appid'] = $this->appid;
        $options['mch_id'] = $this->mch_id;
        $options['spbill_create_ip'] = $this->get_client_ip(); //终端IP
        $options['trade_type'] = "MWEB"; //交易类型
        $options['nonce_str'] = strtoupper(md5(time()));
        ksort($options);
        //签名
        $stringA = $this->arr2str($options);
        $stringSignTemp = $stringA . "key=" . $this->key;
        $options['sign'] = strtoupper(md5($stringSignTemp));

        $postData = $this->buildXml($options);
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

        $dataxml = $this->postXmlCurl($url, $postData);
        return $dataxml;
    }

    /**
     * 订单查询支付结果
     * @param array $options
     * out_trade_no 订单号
     */
    public function wechatOrderresult($options)
    {
        $options['appid'] = $this->appid;
        $options['mch_id'] = $this->mch_id;
        $options['nonce_str'] = strtoupper(md5(time()));
        ksort($options);
        //签名
        $stringA = $this->arr2str($options);
        $stringSignTemp = $stringA . "key=" . $this->key;
        $options['sign'] = strtoupper(md5($stringSignTemp));
        $postData = $this->buildXml($options);
        $url = 'https://api.mch.weixin.qq.com/pay/orderquery';
        $dataxml = $this->postXmlCurl($url, $postData);
        return $dataxml;
    }

    /**
     * 关闭订单
     * @param array $options
     * out_trade_no 订单号
     */
    public function wechatCloseOrder($options)
    {
        $options['appid'] = $this->appid;
        $options['mch_id'] = $this->mch_id;
        $options['nonce_str'] = strtoupper(md5(time()));
        ksort($options);
        //签名
        $stringA = $this->arr2str($options);
        $stringSignTemp = $stringA . "key=" . $this->key;
        $options['sign'] = strtoupper(md5($stringSignTemp));
        $postData = $this->buildXml($options);
        $url = 'https://api.mch.weixin.qq.com/pay/closeorder';
        $dataxml = $this->postXmlCurl($url, $postData);
        return $dataxml;
    }

    /*
     *  拼接数组
     */
    public function arr2str($arr)
    {
        $ret = "";
        foreach ($arr as $k => $v) {
            $tmp = $k . "=" . $v . "&";
            $ret .= $tmp;
        }
        return $ret;
    }

    /*
     *  数组转xml
     */
    public function buildXml($data, $wrap = 'xml')
    {
        $str = "<{$wrap}>";
        if (is_array($data)) {
            if ($this->hasIndex($data)) {
                foreach ($data as $k => $v) {
                    $str .= $this->buildXml($v, $k);
                }
            } else {
                foreach ($data as $v) {
                    foreach ($v as $k1 => $v1)
                        $str .= $this->buildXml($v1, $k1);
                }
            }
        } else
            $str .= $data;
        $str .= "</{$wrap}>";
        return $str;
    }

    public function hasIndex($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    //获取用户ip
    public function get_client_ip()
    {
        $cip = "unknown";
        if ($_SERVER['REMOTE_ADDR']) {
            $cip = $_SERVER['REMOTE_ADDR'];
        } elseif (getenv("REMOTE_ADDR")) {
            $cip = getenv("REMOTE_ADDR");
        }
        return $cip;
    }

    public function postXmlCurl($url, $xml)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        $objectxml = (array)simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        return $objectxml;
    }
}
