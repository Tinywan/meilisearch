<?php
/**
 * @desc Meili.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/4/14 14:36
 */
declare(strict_types=1);


namespace Tinywan;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Meili
{
    /**
     * @var \Closure|\Psr\Container\ContainerInterface|null
     */
    private static $container = null;

    /**
     * Meili constructor.
     * @param array $config
     * @param \Closure|\Psr\Container\ContainerInterface|null $container
     */
    public function __construct(array $config, $container = null)
    {

    }

    /**
     * @desc: __callStatic 描述
     * @param string $service
     * @param array $config
     * @return mixed
     */
    public static function __callStatic(string $service, array $config)
    {
        if (!empty($config)) {
            self::config(...$config);
        }

        return self::get($service);
    }

    /**
     * @desc: 初始化配置
     * @param array $config
     * @param \Closure|\Psr\Container\ContainerInterface|null $container
     * @return bool
     * @author Tinywan(ShaoBo Wan)
     */
    public static function config(array $config = [], $container = null): bool
    {
        if (self::hasContainer() && !($config['_force'] ?? false)) {
            return false;
        }

        new self($config, $container);
        return true;
    }

    /**
     * @desc: set 描述
     * @param string $name
     * @param $value
     * @throws \Exception
     * @author Tinywan(ShaoBo Wan)
     */
    public static function set(string $name, $value): void
    {
        try {
            $container = Meili::getContainer();
            // laravel
            // @phpstan-ignore-next-line
            if ($container instanceof LaravelContainer) {
                // @phpstan-ignore-next-line
                $container->singleton($name, $value instanceof Closure ? $value : static fn () => $value);

                return;
            }

            if (method_exists($container, 'set')) {
                $container->set(...func_get_args());
                return;
            }
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @desc: make 描述
     * @param string $service
     * @param array $parameters
     * @return mixed
     * @throws \Exception
     */
    public static function make(string $service, array $parameters = [])
    {
        try {
            $container = Meili::getContainer();

            if (method_exists($container, 'make')) {
                return $container->make(...func_get_args());
            }
        }catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
        $parameters = array_values($parameters);
        return new $service(...$parameters);
    }

    /**
     * @desc 在容器中查找并返回实体标识符对应的对象
     * @param string $service 查找的实体标识符字符串
     * @return mixed
     * @throws NotFoundExceptionInterface  容器中没有实体标识符对应对象时抛出的异常。
     * @throws ContainerExceptionInterface 查找对象过程中发生了其他错误时抛出的异常。
     */
    public static function get(string $service)
    {
        return Meili::getContainer()->get($service);
    }

    /**
     * @desc: 如果容器内有标识符对应的内容时，返回 true，否则，返回 false。
     * @param string $service
     * @return bool
     * @author Tinywan(ShaoBo Wan)
     */
    public static function has(string $service): bool
    {
        return Meili::getContainer()->has($service);
    }

    /**
     * @param \Closure|\Psr\Container\ContainerInterface|null $container
     */
    public static function setContainer($container): void
    {
        self::$container = $container;
    }

    /**
     * @desc: getContainer 描述
     * @return ContainerInterface
     * @throws \Exception
     */
    public static function getContainer(): ContainerInterface
    {
        if (self::$container instanceof ContainerInterface) {
            return self::$container;
        }

        if (self::$container instanceof \Closure) {
            return (self::$container)();
        }

        throw new \Exception('`getContainer()` failed! Maybe you should `setContainer()` first');
    }

    /**
     * @desc: hasContainer 描述
     * @return bool
     */
    public static function hasContainer(): bool
    {
        return self::$container instanceof \Psr\Container\ContainerInterface || self::$container instanceof \Closure;
    }

    /**
     * @desc: clear 描述
     */
    public static function clear(): void
    {
        self::$container = null;
    }

}