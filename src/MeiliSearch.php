<?php
/**
 * @desc MeiliSearch
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/4/14 14:36
 */
declare(strict_types=1);


namespace Tinywan;

use Closure;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tinywan\Contract\ServiceProviderInterface;
use Tinywan\Exception\ContainerException;
use Tinywan\Exception\ContainerNotFoundException;
use Tinywan\Service\ContainerServiceProvider;
use Tinywan\Service\SearchServiceProvider;

class MeiliSearch
{
    /**
     * @var string[]
     */
    protected array $service = [
        SearchServiceProvider::class
    ];

    /**
     * @var string[]
     */
    private array $coreService = [
        ContainerServiceProvider::class
    ];

    /**
     * @var Closure|ContainerInterface|null
     */
    private static $container = null;

    /**
     * Meili constructor.
     * @param array $config
     * @param Closure|ContainerInterface|null $container
     */
    public function __construct(array $config, $container = null)
    {
        $this->registerServices($config, $container);
    }

    /**
     * 这里注册一些相关的服务：Event Log Http
     * @param Closure|ContainerInterface|null $container
     */
    private function registerServices(array $config, $container = null): void
    {
        foreach (array_merge($this->coreService, $this->service) as $service) {
            self::registerService($service, ContainerServiceProvider::class == $service ? $container : $config);
        }
    }

    /**
     * 调用接口注册服务
     * @param mixed $data
     */
    public static function registerService(string $service, $data): void
    {
        $var = new $service();
        if ($var instanceof ServiceProviderInterface) {
            $var->register($data);
        }
    }

    /**
     * @desc: __callStatic 描述
     * @param string $service
     * @param array $config
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function __callStatic(string $service, array $config)
    {
        // 使用自己的的扩展配置文件
        if (!empty($config)) {
            self::config(...$config);
        }
        return self::get($service);
    }

    /**
     * @desc: 初始化配置
     * @param array $config
     * @param Closure|ContainerInterface|null $container
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
     * @throws ContainerException
     * @author Tinywan(ShaoBo Wan)
     */
    public static function set(string $name, $value): void
    {
        try {
            $container = MeiliSearch::getContainer();
            if (method_exists($container, 'set')) {
                $container->set(...func_get_args());
                return;
            }
        } catch (ContainerNotFoundException | \Throwable $e) {
            throw new ContainerException($e->getMessage());
        }
        throw new ContainerException('Current container does NOT support `set` method');
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
            $container = MeiliSearch::getContainer();
            if (method_exists($container, 'make')) {
                return $container->make(...func_get_args());
            }
        }catch (ContainerNotFoundException| \Throwable $e) {
            throw new ContainerException($e->getMessage());
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
        // 注册的服务
        $container = MeiliSearch::getContainer();
        return $container->get($service);
    }

    /**
     * @desc: 如果容器内有标识符对应的内容时，返回 true，否则，返回 false。
     * @param string $service
     * @return bool
     * @throws ContainerNotFoundException
     * @author Tinywan(ShaoBo Wan)
     */
    public static function has(string $service): bool
    {
        return MeiliSearch::getContainer()->has($service);
    }

    /**
     * @param Closure|ContainerInterface|null $container
     */
    public static function setContainer($container): void
    {
        self::$container = $container;
    }

    /**
     * @desc: getContainer 描述
     * @return ContainerInterface
     * @throws ContainerNotFoundException
     */
    public static function getContainer(): ContainerInterface
    {
        if (self::$container instanceof ContainerInterface) {
            return self::$container;
        }

        if (self::$container instanceof Closure) {
            return (self::$container)();
        }

        throw new ContainerNotFoundException('`getContainer()` failed! Maybe you should `setContainer()` first');
    }

    /**
     * @desc: hasContainer 描述
     * @return bool
     */
    public static function hasContainer(): bool
    {
        return self::$container instanceof ContainerInterface || self::$container instanceof Closure;
    }

    /**
     * @desc: clear 描述
     */
    public static function clear(): void
    {
        self::$container = null;
    }

}