<?php


namespace Snailweb\Utils\RunCondition;


class Timer extends AbstractRunCondition
{
    private $maxTime;
    private $softExit;
    private $startTime;

    public function __construct(int $maxTime, $softExit = true)
    {
        $this->maxTime = $maxTime;
        $this->softExit = $softExit;
        parent::__construct();
    }

    protected function buildCondition() : \Closure
    {
        return function() {

            $runTime = time() - $this->startTime;
            if($runTime < $this->maxTime) {
                return true;
            }
            else {

                if ($this->softExit) {
                    return false;
                } else {
                    exit(0);
                }
            }
        };
    }

    protected function initialize()
    {
        $this->startTime = time();
    }
}