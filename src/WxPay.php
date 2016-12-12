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
	public function __construct(array $payment_info = [])
    {
        $notify = new NativePay();
        $input = new WxPayUnifiedOrder();
        $input->SetBody($payment_info['body']);
        $input->SetAttach($payment_info['attach']);
        $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
        $input->SetTotal_fee($payment_info['total']);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("pay");
        $input->SetNotify_url("http://wxpay.shsytour.cn/notify.php");
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id($payment_info['product_id']);
        //$input->SetOut_trade_no("sjz01234567892");
        $result = $notify->GetPayUrl($input);
        return $result['code_url'];
    }
}
