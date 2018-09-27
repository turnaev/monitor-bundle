<?php
/**
 * This file is part of the `tvi/monitor-bundle` project.
 *
 * (c) https://github.com/turnaev/monitor-bundle/graphs/contributors
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Tvi\MonitorBundle\Check\JsonFile;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Tvi\MonitorBundle\Check\CheckConfigAbstract;

/**
 * @author Vladimir Turnaev <turnaev@gmail.com>
 */
class Config extends CheckConfigAbstract
{
    const DESCR =
<<<TXT
        json_file description
TXT;

    const PATH = __DIR__;

    const GROUP = 'sys';
    const CHECK_NAME = 'json_file';

    /**
     * @param NodeDefinition|ArrayNodeDefinition $node
     *
     * @return NodeDefinition
     */
    protected function _check(NodeDefinition $node): NodeDefinition
    {
        $node = $node
            ->children()
                ->arrayNode('check')
                    ->children()
                        ->arrayNode('files')
                            ->isRequired()
                            ->beforeNormalization()
                                ->ifString()
                                ->then(function ($value) {
                                    if(is_string($value))  {
                                        $value = [$value];
                                    }
                                    return $value;
                                })
                                ->end()
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        $this->_group($node);
        $this->_tags($node);
        $this->_label($node);

        return $node;
    }
}
