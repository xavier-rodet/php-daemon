<?php


namespace Snailweb\Utils\RunCondition;


class Timer extends AbstractRunCondition
{
    protected $condition;
    protected $maxTime;
    protected $startTime;

    public function __construct(int $maxTime)
    {
        $this->maxTime = $maxTime;
        parent::__construct();
    }

    protected function buildCondition() : \Closure
    {
        return function() {
            if((time() - $this->startTime) >= ($this->maxTime));
        };
    }

    protected function initialize()
    {
        $this->startTime = time();
    }
}