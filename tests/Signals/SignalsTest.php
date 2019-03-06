<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Tests\Signals;

use PHPUnit\Framework\TestCase;
use Snailweb\Daemon\Signals\Signals;
use Snailweb\Daemon\Tests\AccessProtectedTrait;
use Snailweb\Daemon\Tests\TestIteratorTrait;

/**
 * @internal
 * @coversNothing
 */
class SignalsTest extends TestCase
{
    use TestIteratorTrait {
        testConstruct as testIteratorConstruct;
    }

    public function setUp(): void
    {
        $this->iterator = new Signals([SIGINT, SIGTERM]);
    }

    public function testConstruct()
    {
        $this->testIteratorConstruct();

        $signals = $this->getAttribute($this->iterator, 'signals');
        $this->assertSame([SIGINT, SIGTERM], $signals);
    }


//    public function testAdd()
//    {
//    }
}
