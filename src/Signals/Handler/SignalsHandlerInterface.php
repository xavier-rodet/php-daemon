<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Signals\Handler;

use Snailweb\Daemon\Daemon;
use Snailweb\Daemon\Signals\Signals;

interface SignalsHandlerInterface
{
    public function assign(Signals $signals): void;

    public function handle(int $signal, Daemon $daemon): void;
}
