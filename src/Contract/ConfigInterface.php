<?php
/**
 * @desc ConfigInterface
 * @author Tinywan(ShaoBo Wan)
 * @email 756684177@qq.com
 * @date 2022/4/14 23:00
 */
declare(strict_types=1);

namespace Tinywan\Contract;


interface ConfigInterface
{
    /**
     * @param string $key
     * @param mixed $default default value of the entry when does not found
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void;
}