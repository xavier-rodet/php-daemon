<?php


namespace Snailweb\Utils\Daemon\Tests;


use Snailweb\Utils\AbstractDaemon;

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