<?php
/**
 * @desc ContainerException.php 描述信息
 *
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/4/14 15:54
 */
declare(strict_types=1);

namespace Tinywan\Exception;

use Psr\Container\ContainerExceptionInterface;
use Throwable;

class ContainerException extends \Exception implements ContainerExceptionInterface
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
