<?php

/*
 * This file is part of the Sonata Project package.
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tvi\MonitorBundle\Check\php\ClassExists;

use Tvi\MonitorBundle\Check\CheckInterface;
use Tvi\MonitorBundle\Test\Check\CheckTestCase;
use ZendDiagnostics\Result\FailureInterface;
use ZendDiagnostics\Result\ResultInterface;
use ZendDiagnostics\Result\SuccessInterface;

/**
 * @author Vladimir Turnaev <turnaev@gmail.com>
 */
class Test extends CheckTestCase
{
    public function testIntegration()
    {
        $this->iterateConfTest(__DIR__.'/config.example.yml');
    }

    public function testCheck()
    {
        $check = new Check(self::class);
        $this->assertInstanceOf(CheckInterface::class, $check);
        $this->assertInstanceOf(ResultInterface::class, $check->check());
    }

    public function testCases()
    {
        $check = new Check(self::class);
        $this->assertInstanceOf(SuccessInterface::class, $check->check());

        $check = new Check('note_exuist_class');
        $this->assertInstanceOf(FailureInterface::class, $check->check());

        $check = new Check(['note_exuist_class', self::class]);
        $this->assertInstanceOf(FailureInterface::class, $check->check());
    }
}
