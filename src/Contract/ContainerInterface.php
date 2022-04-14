<?php
/**
 * @desc ContainerInterface.php 描述信息
 *
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/4/14 15:56
 */
declare(strict_types=1);

namespace Tinywan\Contract;

interface ContainerInterface extends \Psr\Container\ContainerInterface
{
    /**
     * @desc factory make 。如果使用PHP-DI，则继承自 interface DI\FactoryInterface.
     * @param string $name
     * @param array $parameters
     * @return mixed
     */
    public function make(string $name, array $parameters = []);

    /**
     * @param mixed $entry
     *
     * @return mixed
     */
    public function set(string $name, $entry);
}
