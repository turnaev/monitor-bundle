<?php

/**
 * This file is part of the `tvi/monitor-bundle` project.
 *
 * (c) https://github.com/turnaev/monitor-bundle/graphs/contributors
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Tvi\MonitorBundle\Check\php\PhpFlag;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Tvi\MonitorBundle\Check\CheckPluginAbstract;

/**
 * @author Vladimir Turnaev <turnaev@gmail.com>
 */
class Plugin extends CheckPluginAbstract
{
    const DESCR =
<<<'TXT'
php_flag description
TXT;

    const PATH = __DIR__;

    const GROUP = 'php';
    const CHECK_NAME = 'core:php_flag';

    /**
     * @param NodeDefinition|ArrayNodeDefinition $node
     *
     * @return NodeDefinition|ArrayNodeDefinition
     */
    protected function _check(NodeDefinition $node): NodeDefinition
    {
        $node = $node
            ->children()
                ->arrayNode('check')
                    ->children()
                        ->arrayNode('settingName')
                            ->isRequired()
                            ->beforeNormalization()
                                ->ifString()
                                ->then(static function ($value) {
                                    if (\is_string($value)) {
                                        $value = [$value];
                                    }

                                    return $value;
                                })
                                ->end()
                            ->prototype('scalar')->end()
                        ->end()
                        ->booleanNode('expectedValue')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end();

        $this->_addition($node);
//        - "%%settingName%%"
//        - "%%expectedValue%%"
        return $node;
    }
}
