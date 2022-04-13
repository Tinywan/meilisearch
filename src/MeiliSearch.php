<?php
/**
 * @desc MeiliSearch.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/4/13 9:38
 */
declare(strict_types=1);


namespace Tinywan;


use MeiliSearch\Client;

class MeiliSearch
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * MeiliSearch constructor.
     */
    public function __construct()
    {
        $config= \config('plugin.tinywan.meilisearch.app.meilisearch', [
            'api' => 'http://127.0.0.1:7700',
            'key' => ''
        ]);
        $this->client = new Client($config['url'],$config['key']);
    }
}