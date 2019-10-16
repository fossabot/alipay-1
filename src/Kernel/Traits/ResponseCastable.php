<?php

namespace Kaylyu\Alipay\Kernel\Traits;

use Kaylyu\Alipay\Kernel\Contracts\Arrayable;
use Kaylyu\Alipay\Kernel\Exceptions\InvalidArgumentException;
use Kaylyu\Alipay\Kernel\Exceptions\InvalidConfigException;
use Kaylyu\Alipay\Kernel\Http\Response;
use Kaylyu\Alipay\Kernel\Support\Collection;
use Psr\Http\Message\ResponseInterface;

/**
 * Trait ResponseCastable.
 */
trait ResponseCastable
{
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param string|null $type
     * @param array $forceArrayKeys
     *
     * @return array|\Kaylyu\Alipay\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \Kaylyu\Alipay\Kernel\Exceptions\InvalidConfigException
     * @throws \Kaylyu\Alipay\Kernel\Exceptions\Exception
     */
    protected function castResponseToType(ResponseInterface $response, $type = null, array $forceArrayKeys = [])
    {
        $response = Response::buildFromPsrResponse($response);
        $response->setForceArrayKeys($forceArrayKeys)->getBody()->rewind();

        switch ($type ?? 'array') {
            case 'collection':
                return $response->toCollection();
            case 'array':
                return $response->toArray();
            case 'object':
                return $response->toObject();
            case 'raw':
                return $response;
            default:
                if (!is_subclass_of($type, Arrayable::class)) {
                    throw new InvalidConfigException(sprintf(
                        'Config key "response_type" classname must be an instanceof %s',
                        Arrayable::class
                    ));
                }

                return new $type($response);
        }
    }

    /**
     * @param mixed $response
     * @param string|null $type
     *
     * @return array|\Kaylyu\Alipay\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \Kaylyu\Alipay\Kernel\Exceptions\InvalidArgumentException
     * @throws \Kaylyu\Alipay\Kernel\Exceptions\InvalidConfigException
     * @throws \Kaylyu\Alipay\Kernel\Exceptions\Exception
     */
    protected function detectAndCastResponseToType($response, $type = null)
    {
        switch (true) {
            case $response instanceof ResponseInterface:
                $response = Response::buildFromPsrResponse($response);

                break;
            case $response instanceof Arrayable:
                $response = new Response(200, [], json_encode($response->toArray()));

                break;
            case ($response instanceof Collection) || is_array($response) || is_object($response):
                $response = new Response(200, [], json_encode($response));

                break;
            case is_scalar($response):
                $response = new Response(200, [], $response);

                break;
            default:
                throw new InvalidArgumentException(sprintf('Unsupported response type "%s"', gettype($response)));
        }

        return $this->castResponseToType($response, $type);
    }

    /**
     * 格式化返回数据
     * @param $response
     * @param $type
     * @author kaylv <kaylv@dayuw.com>
     * @return array|Collection|string
     */
    protected function formatResponseToType($response, $type)
    {
        //获取数据
        $data = is_object($response) ? (array)$response : $response;

        switch ($type ?? 'array') {
            default:
            case 'collection':
                return new Collection($data);
            case 'array':
                return $data;
            case 'object':
                return $response;
            case 'json':
                return json_encode($data, JSON_UNESCAPED_UNICODE);
        }
    }
}
