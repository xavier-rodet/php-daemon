<?php


namespace Snailweb\Daemon\Tests;


use Snailweb\Daemon\AbstractDaemon;

class FoobarDaemon extends AbstractDaemon
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