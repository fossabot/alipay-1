<?php

return [
    'response_type' => env('KAYLYU_ALIPAY_RESPONSE_TYPE', 'collection'), //collection array json object
    'log' => [
        'level' => env('KAYLYU_ALIPAY_LOG_LEVEL', 'debug'),
        'file' => env('KAYLYU_ALIPAY_LOG_FILE', storage_path('logs/kaylu-alipay.log')),
    ],
    'http' => [
        'verify' => env('KAYLYU_ALIPAY_HTTP_VERIFY', false),
        'timeout' => env('KAYLYU_ALIPAY__HTTP_TIMEOUT', 60),
    ],

    //当面付
    'f2fpay' => [
        //签名方式,默认为RSA2(RSA2048)
        'sign_type' => "RSA2",

        //支付宝公钥
        'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAg02engaotvfFa9Nf680s8t4EUX8/qetOGLWrWNW7w4+zE+4uMaifxngkys2DbqSbj7nt/6lC9jArtHJSvn/6Eefh8A+MRtZRbzHlJ7WUl2wS4y6/mZrhP0YtzenHnAtgVpFUXgRQ8ZBZpgAxqNiqWQpjmNBPaQs7G2IRTsgsvH0kNi7PFgwWpsu2zZKfMHr4xHRcU0XsLbFLW59k5w/PsizVoRhBWAmQoltMteqXjAZgk55cRlxwDiAafWrqdJQTIE4GUJ0UiJhv/sTdDHpt+CVSEI2nJOOMrk7tsjhjQ97GKxXyb+Td4VS1HOHt8/jSOD/CSyjYG/v+j45XiA+6RQIDAQAB",

        //商户私钥
        'merchant_private_key' => "MIIEpQIBAAKCAQEAtDGh9DbiLTmLFCE2Cj5AW9xBAu7KOUx7HVy1CjAneM9m+6ZxYqR60yv4FmcJl1fu9k4Pth8BoXsTmjkQ152AVGMWxq9Q8COvjh0iYMZtkjGxURLDza5qyD4kHX+GoLFkoZjy1zJMfOW6gMaS6Xstd6t1i9lyMPz2QmelrMbbrBZM0iGrzcUCc0d7AnnXhxp3eppL0rMK++ZRawqmm1MzGSwDo/phtBPVtMSuHkJL+Tzq1k/FfWTthmIf5O003oaWbXmf/06z4aOSEPvdF/q4owrMPnjdPUU7TWllISSCb3ffjfeHI4WloJea5PURKo2tpPliV3CFdGTT13xvHNmzjwIDAQABAoIBAQCZB2GcDhQtBiZ39PB46H8txDKt8+9nk+0mdXoGafjLn4+8/ZBjjn4E4t1w4RaDIhl9sbOkHlb7NZPjML1973Nxtyk7mN3q4tOPpGxytXGOa4dD6+S/w3VKE4QGTSwfQifNGSB642uT0mF+RaW0hXiKneFY4FUbB2l34aAQyFz8mhLZGiQ2hDQpKvHX8Kn9MpqLdFo/iARSgK9kXJ+fhWKkHSVq4ZMi/17RbGyo6UhK/pbc4LGdUKcE8Pp5/8hdfQW6epHzECxrbBg4XwRcUaOONKuA6JlHHFVg2v5zM7dTs9aXZNBSeTlWgHuF8tLxxnSyMSP0Eqgzi8J/kKkXHOGpAoGBANhgtXRxPzdyeyZNvp7keUOEtko3lRDRGWKJMUmWOxRoBxIfAuJJLz2v2AuWJo6AaeLLkrlPw5hIHacCc0Rxyjnl25Iv06+uxW8sbc5j0VlstETMmiXMsNE3YMNoKw+8Bcq+/XA8r+uwIO4yBHdOznPG6JaM7I9y7zsBjjiiVD0VAoGBANUwtNVTvEgF7ktWTcT6m69EL38NJ17KT1+rODPd2bKtX6VMnfruv1QmAhd2EHvk6TFlEnNhI9FJgkeb98TONENKGKsLgAqiqctjSWCfAOHYo3RDBce1u2AYSSrV9KqoZFv8e8bKBA8Y+3cIkH4qgQ0S1BEdgzEKZ+BOsA4tjT8TAoGBAJKuRHPyTUN+NOOLcnxuL5JIxfZb5Y5n4Uh97k6PK++ycZCkOyVBLWgo61E3cV3KyXz4OberzdFOmeNcct1kMg27t3RLPbprxOWPfcSawBFZ8n5tINYdA4RggRUWaZKaCGcfv9i1GyCv3jL67zId1zB/F67vS3IXHPuoG0xjJq3dAoGAPzfkR+Y+zPTFRx3ejezqwbxsgTZ7WEaR5wK09dxxs+RpmnDv3/twTRnAQQjHtpWY+QbJw+EoZ+VudqGvhXLIWLPeQMp2O7EkvKVCmx5Leq1tOwqs6h3f5I0PFV7A4OJycDycz8QUFA4Kc6/ceS1Ne3z72d9widhWGVP8LRgg7HUCgYEA1ij0go6hXSG+rDsTJJC/tcN03HS7kzyrZ3IvPG1UgO9PydO1aOJXdm/porw4jC/NkrAd8XsbPjfNzOs70zLOEfDxGBdIhZSFKA/0mc7NSIOkuvXIB106vwYuzxAvlOfSleLv7buPgB11f26Zak5Y6042/2QLEQ0bv7xaz2pu2tc=",

        //编码格式
        'charset' => "UTF-8",

        //支付宝网关
        'gateway_url' => "https://openapi.alipaydev.com/gateway.do",

        //应用ID
        'app_id' => "2016101100663012",

        //异步通知地址,只有扫码支付预下单可用
        'notify_url' => "http://www.baidu.com",
    ]
];