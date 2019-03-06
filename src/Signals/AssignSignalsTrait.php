<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Signals;

trait AssignSignalsTrait
{
    private $signals;

    public function setSignals(SignalsInterface $signals): void
    {
        $this->signals = $signals;
    }

    public function getSignals(): SignalsInterface
    {
        return $this->signals;
    }
}
