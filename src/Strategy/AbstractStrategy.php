<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Strategy;

abstract class AbstractStrategy implements StrategyInterface
{
    private $condition;
    private $numberOfIterations;

    public function __construct()
    {
        $this->condition = $this->buildCondition();
        $this->numberOfIterations = 0;
    }

    public function test(): bool
    {
        if (0 === $this->numberOfIterations()) {
            $this->initialize();
        }

        $test = $this->condition->__invoke();
        $this->numberOfIterations++;

        return $test;
    }

    protected function numberOfIterations(): int
    {
        return $this->numberOfIterations;
    }

    abstract protected function buildCondition(): \Closure;

    abstract protected function initialize(): void;
}
