<?php

namespace Snailweb\Daemon\Tests;

use PHPUnit\Framework\TestCase;
use Snailweb\Daemon\Strategy\Iteration;

class DaemonUnitTest extends TestCase
{
    use AccessProtectedTrait;
    
    private $daemon;

    public function setUp() : void
    {
        $this->daemon = $this->getMockForAbstractClass('Snailweb\Daemon\AbstractDaemon');
    }

    public function tearDown() : void
    {
        unset($this->daemon);
    }

    public function testAssureProcessMinExecTime()
    {
        $start = intval(microtime(true) * 1000);
        $this->invokeMethod($this->daemon, 'initProcess');
        $this->invokeMethod($this->daemon, 'assureProcessMinExecTime');
        $end = intval(microtime(true) * 1000);

        $runTime = $end-$start;
        $expectedRunTime = $this->invokeMethod($this->daemon, 'getDefaultOptions')['process_min_exec_time'];

        $this->assertGreaterThanOrEqual($expectedRunTime, $runTime);
    }

    public function testRunOrder()
    {
        $iterations = 3;



        $this->daemon->expects($this->once())
            ->method('setUp');

        $this->daemon->expects($this->once())
            ->method('tearDown');

        $this->daemon->expects($this->exactly($iterations))
            ->method('process');


        // See : https://stackoverflow.com/questions/15712777/phpunit-how-to-test-that-methods-are-called-in-incorrect-order
        $this->daemon->expects($this->at(0))->method('setUp');
        for($i = 1; $i <= $iterations; $i++) {
            $this->daemon->expects($this->at($i))->method('process');
        }
        $this->daemon->expects($this->at($iterations+1))->method('tearDown');

        $this->daemon->run(new Iteration($iterations));

        // TODO : better way to try order / number of call of internal methods ?
//        $this->expectOutputString('setUp_process_process_process_tearDown_');
        // do tests ....
    }
}
