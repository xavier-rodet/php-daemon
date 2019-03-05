<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Tests;

use PHPUnit\Framework\TestCase;
use Snailweb\Daemon\Daemon;
use Snailweb\Daemon\Processor\ProcessorInterface;
use Snailweb\Daemon\Signals\Manager\SignalsManagerInterface;
use Snailweb\Daemon\Strategy\AbstractStrategy;
use Snailweb\Daemon\Strategy\Forever;

/**
 * @internal
 * @coversNothing
 */
class DaemonUnitTest extends TestCase
{
    use AccessProtectedTrait;

    public function testRunWithStrategy()
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

        $strategy = $this->createMock(AbstractStrategy::class);
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

    public function testRunWithoutStrategy()
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $processor->expects($this->once())
            ->method('setUp')
        ;
        $processor->expects($this->atLeastOnce())
            ->method('process')
        ;
        $processor->expects($this->once())
            ->method('tearDown')
        ;

        $signalsManager = $this->createMock(SignalsManagerInterface::class);

        $daemon = new Daemon($processor, $signalsManager);
        $daemon->assignOptions(['run_ttl' => 1]);
        $daemon->run();

        $this->assertSame(new Forever(), $daemon->getStrategy());
    }
}
