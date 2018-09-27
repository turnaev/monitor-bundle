<?php
/**
 * This file is part of the `tvi/monitor-bundle` project.
 *
 * (c) https://github.com/turnaev/monitor-bundle/graphs/contributors
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Tvi\MonitorBundle\Check\DirReadable;

use ZendDiagnostics\Result\Success;
use ZendDiagnostics\Result\SuccessInterface;
use ZendDiagnostics\Result\WarningInterface;
use ZendDiagnostics\Result\SkipInterface;
use ZendDiagnostics\Result\FailureInterface;

use Tvi\MonitorBundle\Check\CheckInterface;
use Tvi\MonitorBundle\Check\CheckTrait;

/**
 * @author Vladimir Turnaev <turnaev@gmail.com>
 */
class Check extends \ZendDiagnostics\Check\AbstractCheck implements CheckInterface
{
    use CheckTrait;

    /**
     * @see \ZendDiagnostics\Check\CheckInterface::check()
     * @return SuccessInterface|WarningInterface|SkipInterface|FailureInterface
     */
    public function check()
    {
        throw new \Tvi\MonitorBundle\Exception\NotImplemented();
        //return new Success();
    }
}
