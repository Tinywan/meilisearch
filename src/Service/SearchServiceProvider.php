<?php
/**
 * @desc SearchServiceProvider.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/4/14 19:51
 */
declare(strict_types=1);


namespace Tinywan\Service;


use GuzzleHttp\Client as GuzzleHttpClient;
use MeiliSearch\Client;
use Tinywan\Exception\ContainerException;
use Tinywan\MeiliSearch;
use Tinywan\Contract\ServiceProviderInterface;

class SearchServiceProvider implements ServiceProviderInterface
{
    /**
     * @desc: register 描述
     * @param null $data
     * @throws ContainerException
     * @author Tinywan(ShaoBo Wan)
     */
    public function register($data = null): void
    {
        $service = new Client($data['url'], $data['key'], new GuzzleHttpClient($data['guzzle']));
        MeiliSearch::set(Client::class,$service);
        MeiliSearch::set('search', $service);
    }
}