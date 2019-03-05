<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Signals\Manager;

use Snailweb\Daemon\Signals\Handler\SignalsHandlerInterface;
use Snailweb\Daemon\Signals\Listener\SignalsListenerInterface;
use Snailweb\Daemon\Signals\SignalsInterface;

interface SignalsManagerInterface
{
    public function getSignals(): SignalsInterface;

    public function getListener(): SignalsListenerInterface;

    public function getHandler(): SignalsHandlerInterface;
}
