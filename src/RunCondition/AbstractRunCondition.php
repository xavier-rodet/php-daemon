<?php


namespace Snailweb\Utils\RunCondition;


abstract class AbstractRunCondition
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
        $this->numberOfIterations++;

        if(1 === $this->numberOfIterations)
            $this->initialize();

        return $this->condition->__invoke();
    }

    protected function numberOfIterations()
    {
        return $this->numberOfIterations;
    }

    abstract protected function buildCondition() : \Closure;

    abstract protected function initialize();
}