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
        $this->daemon->expects($this->any())
            ->method('setUp')
            ->willReturn('setUp');

        $this->daemon->expects($this->any())
            ->method('tearDown')
            ->willReturn('tearDown');

        $this->daemon->expects($this->any())
            ->method('process')
            ->willReturn('process');
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
        $this->daemon->run(new Iterate(5));

        // do tests ....
    }
}
