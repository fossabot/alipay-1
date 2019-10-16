<?php
/**
 * Created by PhpStorm.
 * User: kaylv <kaylv@dayuw.com>
 * Date: 2019/8/30
 * Time: 11:22
 */

namespace Kaylyu\Alipay\Tests\Refund;


use Kaylyu\Alipay\F2fpay\Application;
use Kaylyu\Alipay\Tests\TestCase;

class ClientTest extends TestCase
{
    protected $application;

    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->application = new Application(
            [
                'response_type' => 'collection',
                'log' => [
                    'file' => __DIR__ . '/logs/lvmama.log',
                    'level' => 'debug',
                ],
                'http' => [
                    'base_uri' => '180.168.128.250:8081/scenic/order/',
                    'timeout' => 30,
                ],
            ]
        );
    }

    /**
     * 申请退款接口
     * @author kaylv <kaylv@dayuw.com>
     */
    public function OrderCancel(){


    }
}