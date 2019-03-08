<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Tests\Signals\Manager;

use PHPUnit\Framework\TestCase;
use Snailweb\Daemon\Signals\Handler\SignalsHandlerInterface;
use Snailweb\Daemon\Signals\Listener\SignalsListenerInterface;
use Snailweb\Daemon\Signals\Manager\SignalsManager;
use Snailweb\Daemon\Signals\SignalsInterface;

/**
 * @internal
 */
class SignalsManagerTest extends TestCase
{
    private $signalsManager;

    public function setUp(): void
    {
        $signals = $this->createMock(SignalsInterface::class);
        $signalsListener = $this->createMock(SignalsListenerInterface::class);
        $signalsHandler = $this->createMock(SignalsHandlerInterface::class);

        $this->signalsManager = new SignalsManager($signals, $signalsListener, $signalsHandler);
    }

    public function testSignalsAccessor()
    {
        $signals = $this->createMock(SignalsInterface::class);
        $this->signalsManager->setSignals($signals);

        $this->assertSame($signals, $this->signalsManager->getSignals());
    }

    public function testListenerAccessor()
    {
        $listener = $this->createMock(SignalsListenerInterface::class);
        $this->signalsManager->setListener($listener);

        $this->assertSame($listener, $this->signalsManager->getListener());
    }

    public function testHandlerAccessor()
    {
        $handler = $this->createMock(SignalsHandlerInterface::class);
        $this->signalsManager->setHandler($handler);

        $this->assertSame($handler, $this->signalsManager->getHandler());
    }
}
