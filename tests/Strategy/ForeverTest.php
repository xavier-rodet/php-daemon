<?php

namespace Snailweb\Daemon\Tests\Strategy;

use Snailweb\Daemon\Strategy\Forever;
use PHPUnit\Framework\TestCase;

class ForeverTest extends TestCase
{
    public function testStrategy()
    {
        $strategy = new Forever();
        $this->assertTrue($strategy->test());
    }
}
