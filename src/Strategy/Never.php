<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Strategy;

final class Never extends AbstractStrategy
{
    protected function buildCondition(): \Closure
    {
        return function () {
            return false;
        };
    }

    protected function initialize(): void
    {
    }
}
