<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'console.command.public_alias.monitor.command.health_check' shared service.

return $this->services['console.command.public_alias.monitor.command.health_check'] = new \MonitorBundle\Command\HealthCheckCommand(($this->services['monitor.runner_manager'] ?? $this->services['monitor.runner_manager'] = new \MonitorBundle\Runner\RunnerManager($this)), $this);
