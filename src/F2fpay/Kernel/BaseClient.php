<?php

namespace Kaylyu\Alipay\F2fpay\Kernel;

use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Kaylyu\Alipay\F2fpay\Base\Model\Result\AlipayF2FPayResult;
use Kaylyu\Alipay\Kernel\Exceptions\Exception;
use Kaylyu\Alipay\Kernel\ServiceContainer;
use Kaylyu\Alipay\Kernel\Support\Collection;
use Kaylyu\Alipay\Kernel\Traits\HasHttpRequests;

/**
 * Class BaseClient.
 */
class BaseClient
{
    //支付宝网关地址
    public $gatewayUrl = "https://openapi.alipay.com/gateway.do";

    //异步通知回调地址
    public $notifyUrl;

    //签名类型
    public $signType;

    //支付宝公钥地址
    public $alipayRsaPublicKey;

    //商户私钥地址
    public $rsaPrivateKey;

    //应用id
    public $appId;

    //编码格式
    public $charset = "UTF-8";

    public $token = null;

    //返回数据格式
    public $format = "json";

    use HasHttpRequests {
        request as performRequest;
    }

    /**
     * @var \Kaylyu\Alipay\Kernel\ServiceContainer
     */
    protected $app;

    /**
     * @var
     */
    protected $baseUri;

    /**
     * BaseClient constructor.
     *
     * @param \Kaylyu\Alipay\Kernel\ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    /**
     * @param $request
     * @param null $appAuthToken
     * @author kaylv <kaylv@dayuw.com>
     * @return array|Collection|string
     */
    public function httpPost($request, $appAuthToken = null)
    {
        return $this->request($request, 'POST', $appAuthToken);
    }

    /**
     * @param $request
     * @param string $method
     * @param null $appAuthToken
     * @author kaylv <kaylv@dayuw.com>
     * @return array|Collection|string
     */
    public function request($request, string $method = 'GET', $appAuthToken = null)
    {
        if (empty($this->middlewares)) {
            $this->registerHttpMiddlewares();
        }

        //加载配置
        $this->loadConfig();

        //组装请求数据
        $aop = new AopClient();
        $aop->gatewayUrl = $this->gatewayUrl;
        $aop->appId = $this->appId;
        $aop->signType = $this->signType;
        $aop->rsaPrivateKey = $this->rsaPrivateKey;
        $aop->alipayrsaPublicKey = $this->alipayRsaPublicKey;
        $aop->apiVersion = "1.0";
        $aop->charset = $this->charset;
        $aop->format = $this->format;
        // 开启页面信息输出
        $aop->debugInfo = true;

        //准备请求参数
        list($requestUrl, $apiParams) = $aop->requestPrepare($request, null, $appAuthToken);

        //发起HTTP请求
        $key = 'query';
        if ($method == 'POST') {
            $key = 'form_params';
        }
        $response = $this->performRequest($requestUrl, $method, [$key => $apiParams]);

        //验签解密
        $response = $aop->responseHandle($request, $response->getBody()->getContents());

        //
        return $response;
    }

    /**
     * Register Guzzle middlewares.
     */
    protected function registerHttpMiddlewares()
    {
        // log
        $this->pushMiddleware($this->logMiddleware(), 'log');
    }

    /**
     * Log the request.
     *
     * @return \Closure
     */
    protected function logMiddleware()
    {
        $formatter = new MessageFormatter($this->app['config']['http.log_template'] ?? MessageFormatter::DEBUG);

        return Middleware::log($this->app['logger'], $formatter);
    }

    /**
     * 加载配置
     * @author kaylv <kaylv@dayuw.com>
     * @throws Exception
     */
    private function loadConfig()
    {
        //获取当面付配置
        $f2fpay = $this->app->getF2fpay();

        //获取配置参数
        $this->gatewayUrl = $f2fpay['gateway_url'];
        $this->appId = $f2fpay['app_id'];
        $this->signType = $f2fpay['sign_type'];
        $this->rsaPrivateKey = $f2fpay['merchant_private_key'];
        $this->alipayPublicKey = $f2fpay['alipay_public_key'];
        $this->charset = $f2fpay['charset'];
        $this->notifyUrl = $f2fpay['notify_url'];

        if (empty($this->appId) || trim($this->appId) == "") {
            throw new Exception("appid should not be NULL!");
        }
        if (empty($this->rsaPrivateKey) || trim($this->rsaPrivateKey) == "") {
            throw new Exception("merchant_private_key should not be NULL!");
        }
        if (empty($this->alipayPublicKey) || trim($this->alipayPublicKey) == "") {
            throw new Exception("alipay_public_key should not be NULL!");
        }
        if (empty($this->charset) || trim($this->charset) == "") {
            throw new Exception("charset should not be NULL!");
        }
        if (empty($this->signType) || trim($this->signType) == "") {
            throw new Exception("sign_type should not be NULL");
        }
        if (empty($this->gatewayUrl) || trim($this->gatewayUrl) == "") {
            throw new Exception("gateway_url should not be NULL");
        }
    }
}
