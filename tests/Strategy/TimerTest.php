<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Tests\Strategy;

use PHPUnit\Framework\TestCase;
use Snailweb\Daemon\Strategy\Timer;

/**
 * @internal
 * @coversNothing
 */
class TimerTest extends TestCase
{
    /**
     * @dataProvider  timeValues
     *
     * @param int $maxTime
     */
    public function testStrategy(int $maxTime)
    {
        $strategy = new Timer($maxTime);

        $startTime = time();
        while ((time() - $startTime) < $maxTime) {
            $this->assertTrue($strategy->test(), sprintf('at time %d/%d seconds', (time() - $startTime), $maxTime));
            usleep(1 * 100 * 1000); // 100ms
        }
        $this->assertFalse($strategy->test(), sprintf('at time %d/%d seconds', (time() - $startTime), $maxTime));
    }

    public function timeValues()
    {
        return [
            [1],
            [2],
            [3],
        ];
    }
}
