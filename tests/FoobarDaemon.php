<?php


namespace Snailweb\Utils\Daemon\Tests;


use Snailweb\Utils\Daemon;

class FoobarDaemon extends Daemon
{

    protected function process()
    {
        echo 'bar';
    }

    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }
}