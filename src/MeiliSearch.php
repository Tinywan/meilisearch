<?php
/**
 * @desc MeiliSearch.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/4/13 9:38
 */
declare(strict_types=1);

namespace Tinywan;

use GuzzleHttp\Client as GuzzleHttpClient;
use MeiliSearch\Client;
use MeiliSearch\Search\SearchResult;

class MeiliSearch
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected  $index;

    /**
     * 指定查询数量
     * @var int
     */
    private $limit;

    /**
     * @var array
     */
    private $attributesToHighlight;

    /**
     * @var array|string[]
     */
    private $facetsDistribution;

    /**
     * 指定查询字段
     * @var array
     */
    private $field;

    /**
     * @var
     */
    private $query;

    /**
     * @var array
     */
    private $sorts;

    /**
     * MeiliSearch constructor.
     */
    public function __construct()
    {
        $config= \config('plugin.tinywan.meilisearch.app.meilisearch', [
            'url' => 'http://127.0.0.1:7700',
            'key' => ''
        ]);
        $this->client = new Client($config['url'], $config['key'], new GuzzleHttpClient($config['guzzle']));
    }

    /**
     * @desc: create index
     * @param string $name
     * @return $this
     */
    public function index(string $name): MeiliSearch
    {
        $this->index = $name;
        return $this;
    }

    /**
     * create documents
     * @param array $documents
     * @return MeiliSearch
     */
    public function addDocuments(array $documents): MeiliSearch
    {
        $index = $this->client->index($this->index);
        if (!empty($documents)) {
            $index->addDocuments($documents);
        }
        return $this;
    }

    /**
     * search documents
     * @param string|null $query
     * @param array $searchParams
     * @param array $options
     * @return SearchResult|array
     */
    public function search(?string $query, array $searchParams = [], array $options = [])
    {
        return $this->client->index($this->index)->search($query, $searchParams, $options);
    }

    /**
     * 设置查询显示的属性
     * @param array $field
     * @return MeiliSearch
     */
    public function field(array $field = []): MeiliSearch
    {
        $this->field = $field;
        return $this;
    }

    /**
     * 设置排序
     * @param $column
     * @param string $rank
     * @return $this
     */
    public function order($column, string $rank = 'asc'): MeiliSearch
    {
        $this->sorts[] = sprintf("%s:%s", $column, $rank);
        return $this;
    }

    /**
     * 查询关键词
     * @param string $keywords
     * @return MeiliSearch
     */
    public function query(string $keywords): MeiliSearch
    {
        $this->query = $keywords;
        return $this;
    }

    /**
     * @desc: 设置查询数量
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): MeiliSearch
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @desc: 高亮查询
     * @param array $attributes
     * @return $this
     */
    public function highlight(array $attributes = []): MeiliSearch
    {
        $this->attributesToHighlight = $attributes;
        return $this;
    }

    /**
     * @desc: facets 描述
     * @param array|string[] $attributes
     * @return $this
     */
    public function facets(array $attributes = ['*']): MeiliSearch
    {
        $this->facetsDistribution = $attributes;
        return $this;
    }

    /**
     * @desc: select
     * @return array
     */
    public function select(): array
    {
        $filters = [
//            'filter' => $this->filters(),
            'limit' => $this->limit
        ];
        if(!empty($this->sorts)){
            $filters['sort'] = $this->sorts;
        }
        if(!empty($this->attributesToHighlight)){
            $filters['attributesToHighlight'] = $this->attributesToHighlight;
        }
        if(!empty($this->field)){
            $filters['attributesToRetrieve'] = $this->field;
        }
        if(!empty($this->facetsDistribution)){
            $filters['facetsDistribution'] = $this->facetsDistribution;
        }
        return $this->rawSearch(array_filter($filters));
    }

    /**
     * Perform the given search on the engine.
     * @param array $searchParams
     * @return array
     */
    protected function rawSearch(array $searchParams = []): array
    {
        $meilisearch = $this->client->index($this->index);
        return $meilisearch->rawSearch($this->query, $searchParams);
    }

    /**
     * @desc: __call 描述
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->client->$method(...$parameters);
    }
}