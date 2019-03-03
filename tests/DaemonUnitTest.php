<?php

namespace Snailweb\Utils\Daemon\Tests;

use PHPUnit\Framework\TestCase;
use Snailweb\Utils\RunCondition\Iterate;

class DaemonUnitTest extends TestCase
{
    use AccessProtectedTrait;
    
    private $daemon;

    public function setUp() : void
    {
//        $this->daemon = new FoobarDaemon();
        $this->daemon = $this->getMockForAbstractClass('Snailweb\Utils\AbstractDaemon');
    }

    public function tearDown() : void
    {
        $this->daemon = null;
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

    public function testRun()
    {
        $this->daemon->expects($this->once())
            ->method('setUp')
            ->willReturnCallback(function() { echo 'setUp_'; });

        $this->daemon->expects($this->once())
            ->method('tearDown')
            ->willReturnCallback(function() { echo 'tearDown_'; });

        $this->daemon->expects($this->any()) // TODO 3 times ?
            ->method('process')
            ->willReturnCallback(function() { echo 'process_'; });

        $this->daemon->run(new Iterate(3));

        // TODO : better way to try order / number of call of internal methods ?
        $this->expectOutputString('setUp_process_process_process_tearDown_');
        // do tests ....
    }
}
