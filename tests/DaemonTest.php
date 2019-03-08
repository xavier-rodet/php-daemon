<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Tests;

use PHPUnit\Framework\TestCase;
use Snailweb\Daemon\Daemon;
use Snailweb\Daemon\Processor\ProcessorInterface;
use Snailweb\Daemon\Signals\Handler\SignalsHandlerInterface;
use Snailweb\Daemon\Signals\Listener\SignalsListenerInterface;
use Snailweb\Daemon\Signals\Manager\SignalsManager;
use Snailweb\Daemon\Signals\SignalsInterface;
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
        $processor->expects($this->exactly(2))
            ->method('process')
        ;
        $processor->expects($this->once())
            ->method('tearDown')
        ;

        $nbTest = 2;
        $strategy = $this->createMock(StrategyInterface::class);
        $strategy->expects($this->atLeastOnce())
            ->method('test')
            ->willReturnCallback(function () use (&$nbTest) {
                $nbTest--;

                return $nbTest >= 0;
            })
        ;

        $daemon = new Daemon($processor);
        $daemon->run($strategy);
    }

    public function testDefaultStrategy()
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $daemon = new Daemon($processor);

        $this->assertEquals(new Forever(), $daemon->getStrategy());
    }

    public function testOptionsAccessor()
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $daemon = new Daemon($processor);

        $options = [
            'run_ttl' => 10, // seconds (1 day)
            'run_memory_limit' => 20, // MB
            'process_min_exec_time' => 30, // milliseconds
        ];
        $daemon->setOptions($options);

        $this->assertSame($options, $daemon->getOptions());
    }

    public function testInvalidSetOptions()
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $daemon = new Daemon($processor);

        $options = [
            'FAKE_OPTION' => 10,
        ];

        $this->expectException('InvalidArgumentException');

        $daemon->setOptions($options);
    }

    public function testProcessAccessor()
    {
        $initProcessor = $this->createMock(ProcessorInterface::class);
        $daemon = new Daemon($initProcessor);

        $processor = $this->createMock(ProcessorInterface::class);
        $daemon->setProcessor($processor);

        $this->assertSame($processor, $daemon->getProcessor());
    }

    public function testStrategyAccessor()
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $daemon = new Daemon($processor);

        $strategy = $this->createMock(StrategyInterface::class);
        $daemon->setStrategy($strategy);

        $this->assertSame($strategy, $daemon->getStrategy());
    }

    public function testUpdate()
    {
        $signal = SIGINT;

        $signals = $this->createMock(SignalsInterface::class);
        $signalListener = $this->createMock(SignalsListenerInterface::class);
        $signalsHandler = $this->createMock(SignalsHandlerInterface::class);

        $signalsManager = new SignalsManager($signals, $signalListener, $signalsHandler);

        $processor = $this->createMock(ProcessorInterface::class);
        $daemon = new Daemon($processor, $signalsManager);

        $signalsHandler->expects($this->once())
            ->method('handle')
            ->with($signal, $daemon)
        ;

        $daemon->update($signalListener, $signal);
    }
}
