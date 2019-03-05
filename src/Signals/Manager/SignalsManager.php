<?php


namespace Snailweb\Daemon\Signals\Manager;


use Snailweb\Daemon\Signals\Handler\AbstractSignalsHandler;
use Snailweb\Daemon\Signals\Listener\SignalsListener;
use Snailweb\Daemon\Signals\Signals;

final class SignalsManager implements SignalsManagerInterface
{
    private $signals;
    private $listener;
    private $handler;

    public function __construct(Signals $signals, SignalsListener $listener, \SignalsHandler $handler)
    {
        $this->signals = $signals;

        $this->listener = $listener;
        $this->listener->assign($signals);

        $this->handler = $handler;
        $this->handler->assign($signals);
    }

    public function getSignals(): Signals
    {
        return $this->signals;
    }

    public function getListener(): SignalsListener
    {
        return $this->listener;
    }

    public function getHandler(): AbstractSignalsHandler
    {
        return $this->handler;
    }
}