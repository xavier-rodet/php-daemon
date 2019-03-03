<?php


namespace Snailweb\Utils\RunCondition;


class Iterate extends AbstractRunCondition
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