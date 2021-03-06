<?php
/**
 * @desc HttpServiceProvider
 * @author Tinywan(ShaoBo Wan)
 * @email 756684177@qq.com
 * @date 2022/4/14 22:58
 */
declare(strict_types=1);

namespace Tinywan\Service;


use GuzzleHttp\Client;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tinywan\Contract\ConfigInterface;
use Tinywan\Contract\HttpClientInterface;
use Tinywan\Contract\ServiceProviderInterface;
use Tinywan\Exception\ContainerException;
use Tinywan\MeiliSearch;

class HttpServiceProvider implements ServiceProviderInterface
{
    /**
     * @param null $data
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ContainerException
     */
    public function register($data = null): void
    {
        $config = MeiliSearch::get(ConfigInterface::class);

        if (class_exists(Client::class)) {
            $service = new Client($config->get('http', []));

            MeiliSearch::set(HttpClientInterface::class, $service);
        }
    }
}