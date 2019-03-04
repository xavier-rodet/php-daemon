<?php


namespace Snailweb\Daemon\Strategy;


class Iteration extends AbstractStrategy
{
    protected $maxIterations;

    public function __construct(int $maxIterations = 1)
    {
        $this->maxIterations = $maxIterations;
        parent::__construct();
    }

    protected function buildCondition() : \Closure
    {
        return function() {
            return ($this->numberOfIterations() < $this->maxIterations);
        };
    }

    protected function initialize()
    {
    }
}