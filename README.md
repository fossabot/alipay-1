<h1 align="center"> Alipay </h1>

基于Laravel框架的支付宝支付SDK，目前仅支持当面付

## Installation
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2FJustshunjian%2Falipay.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2FJustshunjian%2Falipay?ref=badge_shield)


```shell
$ composer require "kaylyu/alipay:~1.0" -vvv
```
## Config
- 配置 可以拷贝config/kaylu-alipay.php 到 laravel 目录config下面
```php
[
    'response_type' => 'collection',//collection array json object
    'log' => [
        'file' => __DIR__ . '/logs/kaylu-alipay.log',
        'level' => 'debug',
    ],
    'http' => [
        'timeout' => 30,
    ],
    //当面付
    'f2fpay' => [
        //签名方式,默认为RSA2(RSA2048)
        'sign_type' => "RSA2",

        //支付宝公钥
        'alipay_public_key' => "",

        //商户私钥
        'merchant_private_key' => "",

        //编码格式
        'charset' => "UTF-8",

        //支付宝网关
        'gateway_url' => "https://openapi.alipaydev.com/gateway.do",

        //应用ID
        'app_id' => "",

        //异步通知地址,只有扫码支付预下单可用
        'notify_url' => "http://www.baidu.com",
    ]
]
```

## Usage
- 在config\app.php注册对应Provider
    Kaylyu\Alipay\F2fpay\ServiceProvider::class
    
    
- 使用
```php
    //统一收单线下交易预创建（扫码支付）
    app('kaylu.alipay.f2fpay')->order->qrPay

```


## License
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2FJustshunjian%2Falipay.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2FJustshunjian%2Falipay?ref=badge_large)