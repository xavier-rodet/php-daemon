<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Strategy;

interface StrategyInterface
{
    public function test(): bool;
}
