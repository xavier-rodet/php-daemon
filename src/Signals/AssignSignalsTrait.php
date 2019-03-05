<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Signals;

trait AssignSignalsTrait
{
    private $signals;

    public function assign(Signals $signals): void
    {
        $this->signals = $signals;
    }
}
