<?php

namespace Snailweb\Utils\Daemon\Tests;

use PHPUnit\Framework\TestCase;

class DaemonUnitTest extends TestCase
{
    use AccessProtected;
    
    private $daemon;

    public function setUp() : void
    {
        $this->daemon = new FoobarDaemon();
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
}
