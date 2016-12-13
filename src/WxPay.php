<?php
namespace Sanyc\WxPay;

use think\Request;
use Carbon\Carbon;
use think\Config;

use Sanyc\WxPay\sdk\WxPayUnifiedOrder;
use Sanyc\WxPay\sdk\WxPayConfig;
use Sanyc\WxPay\sdk\NativePay;

/**
 * 微信支付
 */
class WxPay 
{
    private $url = '';
    const TIME_EXPIRE = 600;
    const BODY = '双辉旅程网';

    public function __construct(array $payment_info = [])
    {
        $notify = new NativePay();
        $input = new WxPayUnifiedOrder();
        if (isset($payment_info['body'])) {
            $input->SetBody($payment_info['body']);
        }else{
            $input->SetBody(self::BODY);
        }
        if (isset($payment_info['attach'])) {
            $input->SetAttach($payment_info['attach']);
        }
        $input->SetOut_trade_no(WxPayConfig::getConfig('MCHID').date("YmdHis"));
        $input->SetTotal_fee($payment_info['total']);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + self::TIME_EXPIRE));
        $input->SetGoods_tag("pay");
        if (isset($payment_info['notify_url'])) {
            $input->SetNotify_url($payment_info['notify_url']);
        } 
        if (isset($payment_info['native'])) {
            $input->SetTrade_type($payment_info['native']);
        }else{
            $input->SetTrade_type("NATIVE");
        }
        if (isset($payment_info['product_id'])) {
            $input->SetProduct_id($payment_info['product_id']);
        }else{
            $input->SetProduct_id('100');
        }
        //$input->SetOut_trade_no("sjz01234567892");
        $result = $notify->GetPayUrl($input);
        $this->url = $result['code_url'];
    }
    public function getPayment()
    {
        return $this->url;
    }
}
