<?php


namespace Snailweb\Daemon\Signals\Manager;


use Snailweb\Daemon\Signals\Handler\AbstractSignalsHandler;
use Snailweb\Daemon\Signals\Listener\SignalsListener;
use Snailweb\Daemon\Signals\Signals;

interface SignalsManagerInterface
{
    public function getSignals() : Signals;
    public function getListener() : SignalsListener;
    public function getHandler() : AbstractSignalsHandler;
}