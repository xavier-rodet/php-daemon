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
        $this->signals = $signals;

        $this->listener = $listener;
        $this->listener->assign($signals);

        $this->handler = $handler;
        $this->handler->assign($signals);
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
}
