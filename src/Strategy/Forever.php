<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Strategy;

final class Forever extends AbstractStrategy
{
    protected function buildCondition(): \Closure
    {
        return function () {
            return true;
        };
    }

    protected function initialize(): void
    {
    }
}
