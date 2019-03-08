<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Tests\Signals;

use PHPUnit\Framework\TestCase;
use Snailweb\Daemon\Signals\Signals;
use Snailweb\Helpers\Tests\TestIteratorTrait;

/**
 * @internal
 */
class SignalsTest extends TestCase
{
    use TestIteratorTrait {
        testConstruct as testIteratorConstruct;
    }
    private $signals;

    public function setUp(): void
    {
        $this->setUpIterator(Signals::class, 'key', 'signals');
        $this->signals = new Signals([SIGINT, SIGTERM]);
    }

    public function testConstruct()
    {
        $this->testIteratorConstruct();

        $signals = $this->getAttribute($this->signals, 'signals');
        $this->assertSame([SIGINT, SIGTERM], $signals);
    }

    public function toArray()
    {
        $this->assertSame([SIGINT, SIGTERM], $this->signals->toArray());
    }

    public function testAddInvalidSignal()
    {
        $this->expectException('InvalidArgumentException');
        $this->signals->add(15213);
    }

    public function testAddAlreadyExistSignal()
    {
        $this->expectException('InvalidArgumentException');
        $this->signals->add(SIGINT);
    }

    public function testAdd()
    {
        $this->signals->add(SIGHUP);
        $signals = $this->getAttribute($this->signals, 'signals');
        $this->assertTrue(in_array(SIGHUP, $signals));
    }
}
