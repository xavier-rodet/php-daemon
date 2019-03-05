<?php


namespace Snailweb\Daemon\Strategy;


interface StrategyInterface
{
    public function test() : bool;
}