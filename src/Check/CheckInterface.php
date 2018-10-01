<?php

/*
 * This file is part of the Sonata Project package.
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tvi\MonitorBundle\Check;

/**
 * @author Vladimir Turnaev <turnaev@gmail.com>
 */
interface CheckInterface extends \ZendDiagnostics\Check\CheckInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id);

    /**
     * @return string[]
     */
    public function getTags(): array;

    /**
     * @param string[] $tags
     */
    public function setTags(array $tags);

    /**
     * @return string
     */
    public function getGroup(): string;

    /**
     * @param string $group
     */
    public function setGroup(string $group);

    /**
     * @param array $data
     */
    public function setAdditionParams(array $data);
}
