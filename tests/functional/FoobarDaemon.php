<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Tests;

use Snailweb\Daemon\AbstractDaemon;

class FoobarDaemon extends AbstractDaemon
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    protected function process()
    {
        echo 'bar';
    }
}
