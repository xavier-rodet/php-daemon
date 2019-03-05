<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Strategy;

final class Timer extends AbstractStrategy
{
    private $maxTime;
    private $startTime;

    public function __construct(int $maxTime)
    {
        $this->maxTime = $maxTime;
        parent::__construct();
    }

    protected function buildCondition(): \Closure
    {
        return function () {
            $runTime = time() - $this->startTime;

            return $runTime < $this->maxTime;
        };
    }

    protected function initialize(): void
    {
        $this->startTime = time();
    }
}
