<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Signals\Handler;

use Snailweb\Daemon\Daemon;
use Snailweb\Daemon\Signals\SignalsInterface;

interface SignalsHandlerInterface
{
    public function setSignals(SignalsInterface $signals): void;

    public function getSignals(): SignalsInterface;

    public function handle(int $signal, Daemon $daemon): void;
}
