<?php


namespace Snailweb\Daemon;


use Snailweb\Daemon\Strategy\AbstractStrategy;

interface DaemonInterface extends \SplObserver
{
    public function assignOptions(array $options) : void;
    public function run(?AbstractStrategy $strategy) : void;
}