<?php


namespace Snailweb\Utils\RunCondition;


class Forever extends AbstractRunCondition
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