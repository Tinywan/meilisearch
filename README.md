# webman meilisearch plugin

[![Latest Stable Version](http://poser.pugx.org/tinywan/meilisearch/v)](https://packagist.org/packages/tinywan/meilisearch) 
[![Total Downloads](http://poser.pugx.org/tinywan/meilisearch/downloads)](https://packagist.org/packages/tinywan/meilisearch) 
[![Latest Unstable Version](http://poser.pugx.org/tinywan/meilisearch/v/unstable)](https://packagist.org/packages/tinywan/meilisearch) 
[![License](http://poser.pugx.org/tinywan/meilisearch/license)](https://packagist.org/packages/tinywan/meilisearch) 
[![PHP Version Require](http://poser.pugx.org/tinywan/meilisearch/require/php)](https://packagist.org/packages/tinywan/meilisearch)

## 安装

```sh
composer require tinywan/meilisearch
```

## 使用

#### 1. 创建索引

```php
\Tinywan\MeiliSearch::index('webman_2022');
```

#### 2. 添加文档

```php
$documents = [
    ['id' => 1, 'title' => '酒吧墙面装饰美式复古咖啡厅'],
    ['id' => 2, 'title' => '工艺品桌面摆件'],
    ['id' => 3, 'title' => '现代简约三联餐厅壁画玄关挂画'],
    ['id' => 4, 'title' => '现代简约时尚单头餐吊灯创意个性吧台']
];
\Tinywan\MeiliSearch::index('webman_2022')->addDocuments($documents);
```

#### 3. 默认查询（默认20条）

```php
\Tinywan\MeiliSearch::index('webman_2022')->search('桌面摆件')->getRaw();
```
- `getRaw()` 返回数组