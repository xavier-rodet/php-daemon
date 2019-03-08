<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Signals\Handler;

use Snailweb\Daemon\Daemon;

/**
 * @codeCoverageIgnore
 */
final class DefaultSignalHandler extends AbstractSignalsHandler
{
    public function handle(int $signal, Daemon $daemon): void
    {
    }
}
