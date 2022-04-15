<?php
/**
 * @desc 任何属于容器部分的异常都应该实现 ContainerExceptionInterface 接口
 *  例子：
 * （1）如果容器依赖配置文件，而配置文件又存在缺陷时，容器可能会抛出一个实现 ContainerExceptionInterface 接口的 InvalidFileException 异常。
 * （2）如果依赖关系中检测到存在循环依赖，容器可能会抛出一个实现 ContainerExceptionInterface 接口的 CyclicDependencyException 异常。
 * （3）然而，如果抛出异常的代码在容器范围外（例如，初始化对象时抛出异常），这时容器抛出的自定义异常不要求实现 ContainerExceptionInterface 基类接口。
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/4/14 15:54
 */
declare(strict_types=1);

namespace Tinywan\Exception;

use Psr\Container\ContainerExceptionInterface;
use Throwable;

class ContainerException extends \Exception implements ContainerExceptionInterface
{
    /**
     * ContainerException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
