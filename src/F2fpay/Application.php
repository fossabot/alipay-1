<?php

namespace Kaylyu\Alipay\F2fpay;

use Closure;
use Kaylyu\Alipay\Kernel\ServiceContainer;

/**
 * @property Order\Client  $order
 * @property Refund\Client  $refund
 *
 * Class Application.
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = [
        Order\ServiceProvider::class,
        Refund\ServiceProvider::class,
    ];

    /**
     * 订单变化通知 订单取消状态和凭证已发送状态,凭证使用状态等
     * @param Closure $closure
     * @author kaylv <kaylv@dayuw.com>
     * @return string
     */
    public function handleOrder(Closure $closure)
    {
        return (new Notify\Order($this))->handle($closure);
    }

    /**
     * 退款推送通知
     * @param Closure $closure
     * @author kaylv <kaylv@dayuw.com>
     * @return string
     */
    public function handleRefunded(Closure $closure)
    {
        return (new Notify\Refunded($this))->handle($closure);
    }
}
