<?php

/**
 * This file is part of the `tvi/monitor-bundle` project.
 *
 * (c) https://github.com/turnaev/monitor-bundle/graphs/contributors
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Tvi\MonitorBundle\Check\php\SecurityAdvisory;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Tvi\MonitorBundle\Check\CheckPluginAbstract;

/**
 * @author Vladimir Turnaev <turnaev@gmail.com>
 */
class Plugin extends CheckPluginAbstract
{
    const DESCR =
<<<'TXT'
security_advisory description
TXT;

    const PATH = __DIR__;

    const GROUP = 'php';
    const CHECK_NAME = 'core:security_advisory';

    protected function _check(ArrayNodeDefinition $node): ArrayNodeDefinition
    {
        $node = $node
            ->children()
                ->arrayNode('check')
                    ->children()
                        ->scalarNode('lockFilePath')->defaultValue('%kernel.root_dir%/../composer.lock')->end()
                    ->end()
                ->end()
            ->end();

        $this->_addition($node);

        return $node;
    }
}
