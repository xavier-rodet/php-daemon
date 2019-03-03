<?php


namespace Snailweb\Utils\RunCondition;


class Never extends AbstractRunCondition
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