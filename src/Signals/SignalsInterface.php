<?php


namespace Snailweb\Daemon\Signals;


interface SignalsInterface extends \Iterator
{
    public function add(int $signal) : void;
}