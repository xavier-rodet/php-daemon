<?php

namespace Snailweb\Daemon\Tests\Strategy;

use Snailweb\Daemon\Strategy\Never;
use PHPUnit\Framework\TestCase;

class NeverTest extends TestCase
{
    public function testStrategy()
    {
        $strategy = new Never();
        $this->assertFalse($strategy->test());
    }
}
