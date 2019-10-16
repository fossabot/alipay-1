<?php

namespace Kaylyu\Alipay\F2fpay\Notify;

use Closure;
use Exception;
use Kaylyu\Alipay\Kernel\Support\Arr;

class Refunded extends Handler
{
    /**
     * 入口
     * @param Closure $closure
     * @author kaylv <kaylv@dayuw.com>
     * @return string
     */
    public function handle(Closure $closure)
    {
        $this->strict(
            \call_user_func($closure, $this->getMessage(), [$this, 'fail'])
        );

        return $this->toResponse();
    }

    /**
     * @author kaylv <kaylv@dayuw.com>
     * @return string
     */
    public function getRequestKey(){
        return 'order';
    }

    /**
     * 验签过程
     * @param array $message
     * @author kaylv <kaylv@dayuw.com>
     * @throws Exception
     */
    public function validate(array $message)
    {
        //获取通知签名
        $sign = Arr::get($message, 'header.signed');
        //获取通知类型
        $changeType = Arr::get($message, 'body.order.orderId');

        //获取secret
        $secret = $this->app->config->get('secret');

        //封装签名
        $signVerify = md5($secret.$changeType);

        //校验
        if (strtolower($signVerify) !== strtolower($sign)) {
            throw new Exception('Failure to verify signature.', 400);
        }
    }
}