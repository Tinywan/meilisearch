<?php
/**
 * @desc 服务注册接口
 *
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/4/14 15:47
 */
declare(strict_types=1);

namespace Tinywan\Contract;

interface ServiceProviderInterface
{
    /**
     * @param mixed $data
     */
    public function register($data = null): void;
}
