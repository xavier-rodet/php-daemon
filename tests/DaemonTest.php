<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Tests;

use PHPUnit\Framework\TestCase;
use Snailweb\Daemon\Daemon;
use Snailweb\Daemon\Processor\ProcessorInterface;
use Snailweb\Daemon\Signals\Manager\SignalsManagerInterface;
use Snailweb\Daemon\Strategy\Forever;
use Snailweb\Daemon\Strategy\StrategyInterface;

/**
 * @internal
 */
class DaemonTest extends TestCase
{
    public function testRun()
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $processor->expects($this->once())
            ->method('setUp')
        ;
        $processor->expects($this->any())
            ->method('process')
        ;
        $processor->expects($this->once())
            ->method('tearDown')
        ;

        $strategy = $this->createMock(StrategyInterface::class);
        $strategy->expects($this->any())
            ->method('test')
        ;

        $signalsManager = $this->createMock(SignalsManagerInterface::class);
        $daemon = new Daemon($processor, $signalsManager);
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

    public function testDaemonDefaultStrategy()
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $signalsManager = $this->createMock(SignalsManagerInterface::class);

        $daemon = new Daemon($processor, $signalsManager);
        $this->assertEquals(new Forever(), $daemon->getStrategy());
    }
}
