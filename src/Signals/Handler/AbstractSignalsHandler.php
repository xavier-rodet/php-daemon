<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Signals\Handler;

use Snailweb\Daemon\Daemon;
use Snailweb\Daemon\Signals\SignalsAccessorTrait;

/**
 * @codeCoverageIgnore
 */
abstract class AbstractSignalsHandler implements SignalsHandlerInterface
{
    use SignalsAccessorTrait;

    abstract public function handle(int $signal, Daemon $daemon): void;
}
