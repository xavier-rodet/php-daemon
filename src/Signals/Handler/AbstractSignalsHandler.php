<?php


namespace Snailweb\Daemon\Signals\Handler;


use Snailweb\Daemon\Daemon;
use Snailweb\Daemon\Signals\AssignSignalsTrait;

abstract class AbstractSignalsHandler implements SignalsHandlerInterface
{
    use AssignSignalsTrait;

    abstract public function handle(int $signal, Daemon $daemon) : void;
}
