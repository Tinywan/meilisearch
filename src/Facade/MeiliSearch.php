<?php
/**
 * @desc MeiliSearch.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/4/13 9:39
 */
declare(strict_types=1);


namespace Tinywan\Facade;

/**
 * @see \Tinywan\MeiliSearch
 * @mixin \Tinywan\MeiliSearch
 * @method static index(string $name) 创建索引
 * @method static addDocuments(array $documents) 添加索引文档
 * @method static search(string $keywords) 查询关键词
 */
class MeiliSearch
{
    /**
     * @var null
     */
    protected static $_instance = null;

    /**
     * @desc: instance 描述
     * @return null
     */
    public static function instance()
    {
        if (!static::$_instance) {
            static::$_instance = new \Tinywan\MeiliSearch();
        }
        return static::$_instance;
    }
    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return static::instance()->{$name}(... $arguments);
    }
}