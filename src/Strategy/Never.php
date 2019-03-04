<?php


namespace Snailweb\Daemon\Strategy;


class Never extends AbstractStrategy
{

    protected function buildCondition(): \Closure
    {
        return function() {
            return false;
        };
    }

    protected function initialize()
    {
    }
}