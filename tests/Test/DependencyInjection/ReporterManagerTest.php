<?php

/**
 * This file is part of the `tvi/monitor-bundle` project.
 *
 * (c) https://github.com/turnaev/monitor-bundle/graphs/contributors
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Tvi\MonitorBundle\Test\DependencyInjection;

use Tvi\MonitorBundle\Reporter\ReporterManager;
use Tvi\MonitorBundle\Test\Base\ExtensionTestCase;

/**
 * @author Vladimir Turnaev <turnaev@gmail.com>
 *
 * @internal
 */
class ReporterManagerTest extends ExtensionTestCase
{
    public function test_get()
    {
        $this->load();
        $this->compile();

        $manager = $this->container->get('tvi_monitor.reporters.manager');

        $this->assertInstanceOf(ReporterManager::class, $manager);
    }

    public function test_reportes()
    {
        $this->load();
        $this->compile();

        $manager = $this->container->get('tvi_monitor.reporters.manager');

        $reporterAliases = $manager->getReporterAliases();
        $this->assertContains('console', $reporterAliases);
        $this->assertContains('nagius', $reporterAliases);
        $this->assertContains('api', $reporterAliases);
        $this->assertNotContains('mailer', $reporterAliases);

        $reporterAliases = $manager->getReporterAliases('console');
        $this->assertNotContains('api', $reporterAliases);
    }

    public function test_mailer_reporter()
    {
        $conf = [
            'reporters' => [
                'mailer' => [
                    'recipient' => 'foo@example.com',
                    'sender' => 'bar@example.com',
                    'subject' => 'Health Check',
                    'send_on_warning' => true,
                ],
            ],
        ];

        $this->load($conf);
        $this->container->set('mailer', $mailer = $this->prophesize('Swift_Mailer')->reveal());

        $this->compile();

        $manager = $this->container->get('tvi_monitor.reporters.manager');

        $reporterAliases = $manager->getReporterAliases();
        $this->assertContains('mailer', $reporterAliases);
    }
}
