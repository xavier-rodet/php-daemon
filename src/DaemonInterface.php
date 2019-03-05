<?php


namespace Snailweb\Daemon;

use Snailweb\Daemon\Strategy\StrategyInterface;

interface DaemonInterface extends \SplObserver
{
    public function assignOptions(array $options) : void;
    public function run(?StrategyInterface $strategy) : void;
}