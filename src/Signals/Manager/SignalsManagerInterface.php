<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Signals\Manager;

use Snailweb\Daemon\Signals\Handler\SignalsHandlerInterface;
use Snailweb\Daemon\Signals\Listener\SignalsListenerInterface;
use Snailweb\Daemon\Signals\SignalsInterface;

interface SignalsManagerInterface
{
    public function getSignals(): SignalsInterface;

    public function setSignals(SignalsInterface $signals): void;

    public function getListener(): SignalsListenerInterface;

    public function setListener(SignalsListenerInterface $listener): void;

    public function getHandler(): SignalsHandlerInterface;

    public function setHandler(SignalsHandlerInterface $handler): void;
}
