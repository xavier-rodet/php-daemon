<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Signals\Manager;

use Snailweb\Daemon\Signals\Handler\SignalsHandlerInterface;
use Snailweb\Daemon\Signals\Listener\SignalsListenerInterface;
use Snailweb\Daemon\Signals\SignalsInterface;

final class SignalsManager implements SignalsManagerInterface
{
    private $signals;
    private $listener;
    private $handler;

    public function __construct(SignalsInterface $signals, SignalsListenerInterface $listener, SignalsHandlerInterface $handler)
    {
        $this->setSignals($signals);

        $listener->setSignals($signals);
        $this->setListener($listener);

        $handler->setSignals($signals);
        $this->setHandler($handler);

    }

    public function getSignals(): SignalsInterface
    {
        return $this->signals;
    }

    public function getListener(): SignalsListenerInterface
    {
        return $this->listener;
    }

    public function getHandler(): SignalsHandlerInterface
    {
        return $this->handler;
    }

    public function setSignals(SignalsInterface $signals): void
    {
        $this->signals = $signals;
    }

    public function setListener(SignalsListenerInterface $listener): void
    {
        $this->listener = $listener;
    }

    public function setHandler(SignalsHandlerInterface $handler): void
    {
        $this->handler = $handler;
    }
}
