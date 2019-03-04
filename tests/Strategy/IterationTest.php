<?php

namespace Snailweb\Daemon\Tests\Strategy;

use Snailweb\Daemon\Strategy\Iteration;
use PHPUnit\Framework\TestCase;

class IterationTest extends TestCase
{
    protected $strategy;

    public function setUp() : void
    {
        $this->strategy = $this->getMockClass('Snailweb\Daemon\Strategy\Iteration');
    }

    public function tearDown() : void
    {
        unset($this->strategy);
    }

    public function testStrategy()
    {
        
    }
}
