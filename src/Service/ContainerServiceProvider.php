<?php
/**
 * @desc ContainerServiceProvider.php 描述信息
 *
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/4/14 15:49
 */
declare(strict_types=1);

namespace Tinywan\Service;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Tinywan\Contract\ServiceProviderInterface;
use Tinywan\Exception\ContainerException;
use Tinywan\Exception\ContainerNotFoundException;
use Tinywan\MeiliSearch;
use support\Container as WebmanContainer;

class ContainerServiceProvider implements ServiceProviderInterface
{
    /**
     * @var string[]
     */
    private array $detectApplication = [
        'webman' => WebmanContainer::class,
    ];

    /**
     * @desc: register 描述
     * @param null $data
     * @throws ContainerException
     */
    public function register($data = null): void
    {
        // 已经祖册，直接返回
        if ($data instanceof ContainerInterface || $data instanceof \Closure) {
            MeiliSearch::setContainer($data);

            return;
        }

        if (MeiliSearch::hasContainer()) {
            return;
        }

        foreach ($this->detectApplication as $framework => $application) {
            $method = $framework.'Application';

            if (class_exists($application) && method_exists($this, $method) && $this->{$method}()) {
                return;
            }
        }

        // 默认注册
        $this->defaultRegister();
    }

    /**
     * @desc: webmanApplication 描述
     * @return bool
     * @throws ContainerException
     * @throws ContainerNotFoundException
     */
    protected function webmanApplication(): bool
    {
        MeiliSearch::setContainer(static fn () => WebmanContainer::instance());
        MeiliSearch::set(\Tinywan\Contract\ContainerInterface::class, WebmanContainer::instance());

        if (!MeiliSearch::has(ContainerInterface::class)) {
            MeiliSearch::set(ContainerInterface::class, WebmanContainer::instance());
        }

        return true;
    }

    /**
     * @desc: 默认注册 defaultRegister
     *
     * @throws ContainerException
     */
    protected function defaultRegister(): void
    {
        if (!class_exists(ContainerBuilder::class)) {
            throw new ContainerNotFoundException('Init failed! Maybe you should install `php-di/php-di` first');
        }

        try {
            $builder = new ContainerBuilder();
            $container = $builder->build();
            // 配置 ContainerInterface PHP-DI 应该通过配置自动注入到 StoreService 中
            $container->set(\Psr\Container\ContainerInterface::class, $container);
            $container->set(\Tinywan\Contract\ContainerInterface::class, $container);
            MeiliSearch::setContainer($container);
        } catch (\Throwable $e) {
            throw new ContainerException($e->getMessage());
        }
    }
}
