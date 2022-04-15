<?php
/**
 * @desc ContainerNotFoundException.php 描述信息
 *
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/4/14 15:52
 */
declare(strict_types=1);

namespace Tinywan\Exception;

use Psr\Container\NotFoundExceptionInterface;

class ContainerNotFoundException extends ContainerException implements NotFoundExceptionInterface
{
}
