<?php

/*
 * This file is part of the Sonata Project package.
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tvi\MonitorBundle\Check\fs\DiskUsage;

use Tvi\MonitorBundle\Check\CheckInterface;
use Tvi\MonitorBundle\Check\CheckTrait;
use ZendDiagnostics\Result\Failure;

use ZendDiagnostics\Result\Success;
use ZendDiagnostics\Result\Warning;

/**
 * @author Vladimir Turnaev <turnaev@gmail.com>
 */
class Check extends \ZendDiagnostics\Check\DiskUsage implements CheckInterface
{
    use CheckTrait;

    /**
     * {@inheritdoc}
     */
    public function check()
    {
        $df = disk_free_space($this->path);
        $dt = disk_total_space($this->path);

        $du = $dt - $df;
        $dp = round(($du / $dt) * 100, 2);

        if ($dp >= $this->criticalThreshold) {
            return new Failure(sprintf('Disk usage too high: %.2f %%.', $dp), $dp);
        }

        if ($dp >= $this->warningThreshold) {
            return new Warning(sprintf('Disk usage high: %.2f %%.', $dp), $dp);
        }

        return new Success(sprintf('Disk usage is %.5f %%.', $dp), $dp);
    }
}
