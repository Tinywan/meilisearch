<?php
/**
 * @desc MeiliSearch
 *
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/4/14 14:36
 */
declare(strict_types=1);

namespace Tinywan;

use Closure;
use MeiliSearch\Client;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tinywan\Contract\ServiceProviderInterface;
use Tinywan\Exception\ContainerException;
use Tinywan\Exception\ContainerNotFoundException;
use Tinywan\Service\ContainerServiceProvider;
use Tinywan\Service\HttpServiceProvider;
use Tinywan\Service\SearchServiceProvider;

/**
 * @see \Tinywan\MeiliSearch
 * @mixin MeiliSearch
 *
 * @method static Client search(array $config = [], $container = null)
 */
class MeiliSearch
{
    /**
     * @var array|string[]
     */
    protected array $service = [
        SearchServiceProvider::class,
    ];

    /**
     * @var array|string[]
     */
    private array $coreService = [
        ContainerServiceProvider::class,
        HttpServiceProvider::class
    ];

    /**
     * @var Closure|ContainerInterface|null
     */
    private static $container = null;

    /**
     * MeiliSearch constructor.
     *
     * @param Closure|ContainerInterface|null $container
     */
    public function __construct(array $config, $container = null)
    {
        $this->registerServices($config, $container);
    }

    /**
     * @desc: 用静态方式中调用一个不可访问方法时调用
     *
     * @return mixed
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
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
     *
     * @param Closure|ContainerInterface|null $container
     *
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
     * @desc: 设置切换到另一个容器
     *
     * @param string $name
     * @param $value
     *
     * @throws ContainerException
     */
    public static function set(string $name, $value): void
    {
        try {
            $container = MeiliSearch::getContainer();
            // 设置其他容器 $value
            if (method_exists($container, 'set')) {
                $container->set(...func_get_args());

                return;
            }
        } catch (ContainerNotFoundException|\Throwable $e) {
            throw new ContainerException($e->getMessage());
        }

        throw new ContainerException('Current container does NOT support `set` method');
    }

    /**
     * @desc: make 描述
     *
     * @return mixed
     *
     * @throws ContainerException
     */
    public static function make(string $service, array $parameters = [])
    {
        try {
            $container = MeiliSearch::getContainer();
            if (method_exists($container, 'make')) {
                return $container->make(...func_get_args());
            }
        } catch (ContainerNotFoundException|\Throwable $e) {
            throw new ContainerException($e->getMessage());
        }
        $parameters = array_values($parameters);

        return new $service(...$parameters);
    }

    /**
     * @desc 在容器中查找并返回实体标识符对应的对象
     * （1）参数 id 对应的对象在容器中不存在时， get 方法抛出的异常必须实现 Psr\Container\NotFoundExceptionInterface 接口。
     * @param string $service 查找的实体标识符字符串
     *
     * @return mixed
     *
     * @throws NotFoundExceptionInterface  容器中没有实体标识符对应对象时抛出的异常
     * @throws ContainerExceptionInterface 查找对象过程中发生了其他错误时抛出的异常
     */
    public static function get(string $service)
    {
        try {
            return MeiliSearch::getContainer()->get($service);
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $exception){
            // 因为请求的对象存在，所以 NotFoundExceptionInterface 的异常表示这是容器配置错误或者请求对象的依赖不存在。
            throw new ContainerNotFoundException('容器配置错误或者请求对象的依赖不存在');
        }
    }

    /**
     * @desc: 如果容器内有标识符对应的内容时，返回 true，否则，返回 false。
     * （1）如果 has 方法返回 false ， get 方法抛出的异常一定要实现 Psr\Container\NotFoundExceptionInterface 接口。
     * （2）如果 has 方法返回 true，这并不意味 get 会成功且不会抛出异常。如果对象依赖的对象不存在时也会抛出 Psr\Container\NotFoundExceptionInterface 接口的异常。
     * @throws ContainerNotFoundException
     */
    public static function has(string $service): bool
    {
        return MeiliSearch::getContainer()->has($service);
    }

    /**
     * @desc 设置容器
     *
     * @param Closure|ContainerInterface|null $container
     */
    public static function setContainer($container): void
    {
        self::$container = $container;
    }

    /**
     * @desc: getContainer
     *
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

    /**
     * @desc 调用接口注册服务
     *
     * @param string $service
     * @param $data
     */
    public static function registerService(string $service, $data): void
    {
        $var = new $service();
        if ($var instanceof ServiceProviderInterface) {
            $var->register($data);
        }
    }

    /**
     * @desc 这里注册一些相关的服务：Event Log Http.
     *
     * @param Closure|ContainerInterface|null $container
     */
    private function registerServices(array $config, $container = null): void
    {
        foreach (array_merge($this->coreService, $this->service) as $service) {
            self::registerService($service, ContainerServiceProvider::class == $service ? $container : $config);
        }
    }
}
