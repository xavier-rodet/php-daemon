<?php

namespace Snailweb\Daemon\Tests\Strategy;

use Snailweb\Daemon\Strategy\Iteration;
use PHPUnit\Framework\TestCase;

class IterationTest extends TestCase
{
    /**
     * @dataProvider iterationValues
     * @param int $iterations
     */
    public function testStrategy(int $iterations)
    {
        $strategy = new Iteration($iterations);

        // in-range iterations will pass the test
        for($i = 1; $i <= $iterations; $i++) {
            $this->assertTrue($strategy->test(), sprintf('at iteration %d/%d)', $i, $iterations));
        }

        // out-range iteration will fail the test
        $this->assertFalse($strategy->test(), sprintf('at iteration %d/%d', ($iterations+1), $iterations));
    }

    public function iterationValues()
    {
        return [
            [rand(1, 100)],
            [rand(1, 100)],
            [rand(1, 100)],
            [rand(1, 100)],
            [rand(1, 100)],
        ];
    }
}
