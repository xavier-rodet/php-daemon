<?php


namespace Snailweb\Daemon\Strategy;


class Forever extends AbstractStrategy
{
    protected function buildCondition() : \Closure
    {
        return function() {
            return true;
        };
    }

    protected function initialize()
    {
    }
}