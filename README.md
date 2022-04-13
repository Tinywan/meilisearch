# webman meilisearch plugin

[![Latest Stable Version](http://poser.pugx.org/tinywan/meilisearch/v)](https://packagist.org/packages/tinywan/meilisearch) 
[![Total Downloads](http://poser.pugx.org/tinywan/meilisearch/downloads)](https://packagist.org/packages/tinywan/meilisearch) 
[![Latest Unstable Version](http://poser.pugx.org/tinywan/meilisearch/v/unstable)](https://packagist.org/packages/tinywan/meilisearch) 
[![License](http://poser.pugx.org/tinywan/meilisearch/license)](https://packagist.org/packages/tinywan/meilisearch) 
[![PHP Version Require](http://poser.pugx.org/tinywan/meilisearch/require/php)](https://packagist.org/packages/tinywan/meilisearch)

MeiliSearch是一个功能强大，快速，开源，易于使用和部署的搜索引擎。搜索和索引都是高度可定制的。允许输入、过滤器和同义词等特性都是开箱即用的。是近两年开源的项目，同样也支持中文分词，在小数据规模下可以实现比[ElasticSearch](https://www.elastic.co/cn/elasticsearch/)更加快速和易用的搜索体验。更多安装、配置、使用等细节请参考[官方文档](https://docs.meilisearch.com/)或交友网站

## 安装

```sh
composer require tinywan/meilisearch
```

## 使用

#### 1. 创建索引

```php
Tinywan\MeiliSearch::index('webman_2022');
```

#### 2. 添加文档

```php
$documents = [
    ['id' => 1, 'title' => '酒吧墙面装饰美式复古咖啡厅'],
    ['id' => 2, 'title' => '工艺品桌面摆件'],
    ['id' => 3, 'title' => '现代简约三联餐厅壁画玄关挂画'],
    ['id' => 4, 'title' => '现代简约时尚单头餐吊灯创意个性吧台']
];
Tinywan\MeiliSearch::index('webman_2022')->addDocuments($documents);
```

#### 3. 默认查询（默认20条）

```php
Tinywan\MeiliSearch::index('webman_2022')->search('桌面摆件')->getRaw();
```

- `getRaw()` 返回数组

#### 4. 指定关键词查询（默认20条）

```php
Tinywan\MeiliSearch::index('webman_2022')->query('桌面摆件')->select();
```

#### 5. 指定关键词查询（默认20条）

```php
Tinywan\MeiliSearch::index('webman_2022')->query('桌面摆件')->select();
```

#### 6. 查询条数

```php
Tinywan\MeiliSearch::index('webman_2022')->query('桌面摆件')->limit(3)->select();
```

#### 7. 查询指定字段值

```php
Tinywan\MeiliSearch::index('webman_2022')->query('桌面摆件')->field(['title'])->select();
```

## 前端集成

![demo.png](./demo.png);