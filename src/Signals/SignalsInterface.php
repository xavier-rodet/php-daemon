<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Signals;

interface SignalsInterface extends \Iterator
{
    public function add(int $signal): void;

    public function toArray(): array;
}
