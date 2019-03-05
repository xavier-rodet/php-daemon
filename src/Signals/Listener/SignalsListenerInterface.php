<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Signals\Listener;

use Snailweb\Daemon\Signals\Signals;

interface SignalsListenerInterface extends \SplSubject
{
    public function assign(Signals $signals): void;

    public function listen(): void;

    public function getSignal(): int;
}
