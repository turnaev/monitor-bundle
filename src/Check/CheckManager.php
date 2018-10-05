<?php

/**
 * This file is part of the `tvi/monitor-bundle` project.
 *
 * (c) https://github.com/turnaev/monitor-bundle/graphs/contributors
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Tvi\MonitorBundle\Check;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * @author Vladimir Turnaev <turnaev@gmail.com>
 */
class CheckManager implements \ArrayAccess, \Iterator, \Countable
{
    use ContainerAwareTrait;
    use CheckArraybleTrait;

    /**
     * @var Group[]
     */
    protected $groups = [];

    /**
     * @var Group[]
     */
    protected $tags = [];

    public function init(array $tagsMap, array $checkServiceMap)
    {
        $this->setTagsMap($tagsMap);
        $this->setCheckServiceMap($checkServiceMap);
    }

    /**
     * @param ?string|string[] $tags
     * @param ?string|string[] $alias
     * @param ?string|string[] $groups
     *
     * @return Tag[]
     */
    public function findChecks($alias = null, $groups = null, $tags = null): array
    {
        $alias = (array) (null === $alias ? [] : (\is_string($alias) ? [$alias] : $alias));
        $groups = (array) (null === $groups ? [] : (\is_string($groups) ? [$groups] : $groups));
        $tags = (array) (null === $tags ? [] : (\is_string($tags) ? [$tags] : $tags));

        $check = array_filter($this->toArray(), static function (CheckInterface $c) use ($alias, $groups, $tags) {
            $inAlias = ($alias) ? \in_array($c->getId(), $alias, true) : true;
            $inGroups = ($groups) ? \in_array($c->getGroup(), $groups, true) : true;
            $inTags = ($tags) ? (bool) array_intersect($c->getTags(), $tags) : true;

            return $inAlias && $inGroups && $inTags;
        });

        return $check;
    }

    /**
     * @param ?string|string[] $groups
     *
     * @return Group[]
     */
    public function findGroups($groups = null): array
    {
        if ($groups) {
            $groups = \is_string($groups) ? [$groups] : $groups;

            return array_filter($this->groups, static function ($t) use ($groups) {
                return \in_array($t->getName(), $groups, true);
            });
        }

        return $this->groups;
    }

    /**
     * @param ?string|string[] $tags
     *
     * @return Group[]
     */
    public function findTags($tags = null): array
    {
        if ($tags) {
            $tags = \is_string($tags) ? [$tags] : $tags;

            return array_filter($this->tags, static function ($t) use ($tags) {
                return \in_array($t->getName(), $tags, true);
            });
        }

        return $this->tags;
    }

    public function addGroup(Group $group): Group
    {
        return empty($this->groups[$group->getName()]) ? $this->groups[$group->getName()] = $group : $this->groups[$group->getName()];
    }

    public function addTag(Tag $tag): Tag
    {
        return empty($this->tags[$tag->getName()]) ? $this->tags[$tag->getName()] = $tag : $this->tags[$tag->getName()];
    }

    protected function setTagsMap(array $tagsMap)
    {
        foreach ($tagsMap as $tag => $tagSetting) {
            $this->addTag(new Tag($tag));
        }
    }

    /**
     * @param array $checkServiceMap
     */
    protected function setCheckServiceMap($checkServiceMap)
    {
        foreach ($checkServiceMap as $checkId => $check) {
            $serviceId = $check['serviceId'];
            $checkProxy = new Proxy(function () use ($serviceId, $checkId) {
                $this->checks[$checkId] = $this->container->get($serviceId);

                return $this->checks[$checkId];
            });

            $this->checks[$checkId] = $checkProxy;

            foreach ($check['tags'] as $tagName) {
                $tag = $this->addTag(new Tag($tagName));
                $tag->addCheck($checkId, $this->checks[$checkId]);
            }

            $group = $this->addGroup(new Group($check['group']));
            $group->addCheck($checkId, $this->checks[$checkId]);
            $this->groups[$group->getName()] = $group;
        }

        foreach ($this->tags as $id => $tag) {
            if (!\count($tag)) {
                unset($this->tags[$id]);
            }
        }
    }
}
