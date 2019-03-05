<?php

namespace Snailweb\Daemon\Tests;

use PHPUnit\Framework\TestCase;
use Snailweb\Daemon\AbstractDaemon;
use Snailweb\Daemon\Daemon;
use Snailweb\Daemon\DaemonInterface;
use Snailweb\Daemon\Processor\ProcessorInterface;
use Snailweb\Daemon\Strategy\Forever;
use Snailweb\Daemon\Strategy\Iteration;
use Snailweb\Daemon\Strategy\Never;
use Snailweb\Daemon\Strategy\StrategyInterface;

\DG\BypassFinals::enable();

class DaemonUnitTest extends TestCase
{
    use AccessProtectedTrait;

    public function testRunWithStrategy()
    {
        $iterations = 3;

        $processor = $this->createMock(ProcessorInterface::class);
        $processor->expects($this->once())
            ->method('setUp');
        $processor->expects($this->exactly($iterations))
            ->method('process');
        $processor->expects($this->once())
            ->method('tearDown');

        $strategy = $this->getMockBuilder(Iteration::class)
            ->enableProxyingToOriginalMethods()
            ->setConstructorArgs([$iterations])
            ->getMock();
        $strategy->expects($this->exactly($iterations+1))
            ->method('test');


        $daemon = new Daemon($processor);
        $daemon->run($strategy);

        $this->assertSame($strategy, $daemon->getStrategy());
        // Test RunTime ?
        // Test listenSignals

        // setProcessStartTime ?

        // shouldRestart / stop

        // assureProcessMinExecTime



//        $this->initRun($strategy);
//        $this->getProcessor()->setUp();
//
//        while ($this->strategy->test()) {
//            $this->initProcess();
//            $this->getProcessor()->process();
//
//            if ($this->shouldRestart()) {
//                $this->stop();
//            }
//
//            $this->assureProcessMinExecTime();
//        }
//        $this->getProcessor()->tearDown();
    }


    public function testRunWithoutStrategy()
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $processor->expects($this->once())
        ->method('setUp');
        $processor->expects($this->atLeastOnce())
            ->method('process');
        $processor->expects($this->once())
            ->method('tearDown');

        $daemon = new Daemon($processor, ['run_ttl' => 1]);
        $daemon->run();

        $this->assertSame(new Forever(), $daemon->getStrategy());
    }
}
