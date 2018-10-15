<?php

/**
 * This file is part of the `tvi/monitor-bundle` project.
 *
 * (c) https://github.com/turnaev/monitor-bundle/graphs/contributors
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Tvi\MonitorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Tvi\MonitorBundle\Check\CheckInterface;
use Tvi\MonitorBundle\Runner\RunnerManager;

class UIController extends Controller
{
    use TraitCommon;

    /**
     * @var RunnerManager
     */
    protected $runnerManager;

    public function __construct(RunnerManager $runnerManager)
    {
        $this->runnerManager = $runnerManager;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        list($filterIds, $filterChecks, $filterGroups, $filterTags) = $this->getFilterParams($request);
        $filterChecks = $filterChecks ? $filterChecks : $filterIds;

        $groups = $this->runnerManager->findGroups();
        $tags = $this->runnerManager->findTags();
        $checks = $this->runnerManager->findChecks();

        $selectedChecks = $this->runnerManager->findChecksSorted($filterChecks, $filterGroups, $filterTags);

        return $this->render('@TviMonitor/ui/index.html.twig', [
                'groups' => $groups,
                'tags' => $tags,
                'checks' => $checks,
                'filters' => ['checks' => $filterChecks, 'groups' => $filterGroups, 'tags' => $filterTags],
                'selectedChecks' => $selectedChecks,
            ]);
    }
}
