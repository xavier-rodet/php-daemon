<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Signals\Listener;

use Snailweb\Daemon\Signals\SignalsInterface;

interface SignalsListenerInterface extends \SplSubject
{
    public function setSignals(SignalsInterface $signals): void;

    public function getSignals(): SignalsInterface;

    public function intercept(int $signal): void;

    public function listen(): void;
}
