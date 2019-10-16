<?php

namespace Kaylyu\Alipay\F2fpay\Order;

use Kaylyu\Alipay\F2fpay\Base\Aop\Request\AlipayTradePayRequest;
use Kaylyu\Alipay\F2fpay\Base\Aop\Request\AlipayTradePrecreateRequest;
use Kaylyu\Alipay\F2fpay\Base\Aop\Request\AlipayTradeQueryRequest;
use Kaylyu\Alipay\F2fpay\Base\Model\Builder\AlipayTradePayContentBuilder;
use Kaylyu\Alipay\F2fpay\Base\Model\Builder\AlipayTradePrecreateContentBuilder;
use Kaylyu\Alipay\F2fpay\Base\Model\Builder\AlipayTradeQueryContentBuilder;
use Kaylyu\Alipay\F2fpay\Base\Model\Result\AlipayF2FPayResult;
use Kaylyu\Alipay\F2fpay\Kernel\BaseClient;
use function Kaylyu\Alipay\F2fpay\Kernel\Support\querySuccess;
use function Kaylyu\Alipay\F2fpay\Kernel\Support\tradeError;
use function Kaylyu\Alipay\F2fpay\Kernel\Support\tradeSuccess;

/**
 * 订单
 * @author kaylv <kaylv@dayuw.com>
 * @package Kaylyu\Alipay\F2fpay\Order
 */
class Client extends BaseClient
{
    /**
     * 统一收单交易支付接口（条码支付）
     * 收银员使用扫码设备读取用户手机支付宝“付款码”/声波获取设备（如麦克风）读取用户手机支付宝的声波信息后，将二维码或条码信息/声波信息通过本接口上送至支付宝发起支付
     * @param AlipayTradePayContentBuilder $builder
     * @author kaylv <kaylv@dayuw.com>
     * @return array|\Kaylyu\Alipay\Kernel\Support\Collection|string
     */
    public function barPay(AlipayTradePayContentBuilder $builder)
    {
        //创建
        $request = new AlipayTradePayRequest();
        $request->setBizContent($builder->getBizContent());

        //请求
        $response = $this->httpPost($request, $builder->getAppAuthToken());

        //获取
        $data = $response->alipay_trade_pay_response;
        $sign = $response->sign;

        //组装返回数据
        $result = new AlipayF2FPayResult($data, $sign);

        //处理
        if (tradeSuccess($data)) {
            $result->setTradeStatus(AlipayF2FPayResult::ALIPAY_F2FPAY_RESULT_SUCCESS);
        } elseif (tradeError($data)) {
            $result->setTradeStatus(AlipayF2FPayResult::ALIPAY_F2FPAY_RESULT_UNKNOWN);
        } else {
            $result->setTradeStatus(AlipayF2FPayResult::ALIPAY_F2FPAY_RESULT_FAILED);
        }

        return $this->formatResponseToType($result, $this->app->config->get('response_type'));
    }

    /**
     * 统一收单线下交易预创建（扫码支付）
     * 收银员通过收银台或商户后台调用支付宝接口，生成二维码后，展示给用户，由用户扫描二维码完成订单支付
     * @param AlipayTradePrecreateContentBuilder $builder
     * @author kaylv <kaylv@dayuw.com>
     * @return array|\Kaylyu\Alipay\Kernel\Support\Collection|string
     */
    public function qrPay(AlipayTradePrecreateContentBuilder $builder)
    {
        //获取当面付配置
        $f2fpay = $this->app->getF2fpay();

        //创建
        $request = new AlipayTradePrecreateRequest();
        $request->setBizContent($builder->getBizContent());
        $request->setNotifyUrl($f2fpay['notify_url']);

        //请求
        $response = $this->httpPost($request, $builder->getAppAuthToken());

        //获取
        $data = $response->alipay_trade_precreate_response;
        $sign = $response->sign;

        //组装返回数据
        $result = new AlipayF2FPayResult($data, $sign);

        //处理
        if (tradeSuccess($data)) {
            $result->setTradeStatus(AlipayF2FPayResult::ALIPAY_F2FPAY_RESULT_SUCCESS);
        } elseif (tradeError($data)) {
            $result->setTradeStatus(AlipayF2FPayResult::ALIPAY_F2FPAY_RESULT_UNKNOWN);
        } else {
            $result->setTradeStatus(AlipayF2FPayResult::ALIPAY_F2FPAY_RESULT_FAILED);
        }

        return $this->formatResponseToType($result, $this->app->config->get('response_type'));
    }

    /**
     * 统一收单线下交易查询
     * @param AlipayTradeQueryContentBuilder $builder
     * @author kaylv <kaylv@dayuw.com>
     * @return array|\Kaylyu\Alipay\Kernel\Support\Collection|string
     */
    public function query(AlipayTradeQueryContentBuilder $builder)
    {
        //查询
        $request = new AlipayTradeQueryRequest();
        $request->setBizContent($builder->getBizContent());

        //请求
        $response = $this->httpPost($request, $builder->getAppAuthToken());

        //获取
        $data = $response->alipay_trade_query_response;
        $sign = $response->sign;

        //组装返回数据
        $result = new AlipayF2FPayResult($data, $sign);

        //处理
        if (querySuccess($data)) {
            // 查询返回该订单交易支付成功
            $result->setTradeStatus(AlipayF2FPayResult::ALIPAY_F2FPAY_RESULT_SUCCESS);
        } elseif (tradeError($data)) {
            //查询发生异常或无返回，交易状态未知
            $result->setTradeStatus(AlipayF2FPayResult::ALIPAY_F2FPAY_RESULT_UNKNOWN);
        } else {
            //其他情况均表明该订单号交易失败
            $result->setTradeStatus(AlipayF2FPayResult::ALIPAY_F2FPAY_RESULT_FAILED);
        }

        return $this->formatResponseToType($result, $this->app->config->get('response_type'));
    }
}
