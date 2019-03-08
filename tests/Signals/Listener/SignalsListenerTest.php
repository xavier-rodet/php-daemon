<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Tests\Signals\Listener;

use PHPUnit\Framework\TestCase;
use Snailweb\Daemon\DaemonInterface;
use Snailweb\Daemon\Signals\Listener\SignalsListener;
use Snailweb\Daemon\Signals\Signals;
use Snailweb\Helpers\Tests\TestSplSubjectTrait;

/**
 * @internal
 */
class SignalsListenerTest extends TestCase
{
    use TestSplSubjectTrait;

    private $subject;

    public function setUp(): void
    {
        $this->setUpSplSubject(SignalsListener::class, 'daemons');
    }

    // Cannot test pcntl in two tests in same test class ...
//    public function testListenThrowException()
//    {
//        $namespace = preg_replace('/^(.*)\\\\SignalsListener$/', '$1', SignalsListener::class);
//
//        $signals = [SIGINT];
//
//        $builder = new MockBuilder();
//        $builder->setNamespace($namespace)
//            ->setName('pcntl_async_signals')
//            ->setFunction(function (bool $on) {
//            })
//        ;
//        $pcntl_async_signals = $builder->build();
//        $pcntl_async_signals->enable();
//
//        $builder = new MockBuilder();
//        $builder->setNamespace($namespace)
//            ->setName('pcntl_signal')
//            ->setFunction(function (int $signal, array $callableMethod) {
//                return false;
//            })
//        ;
//        $pcntl_signal = $builder->build();
//        $pcntl_signal->enable();
//
//        $this->expectException('RunTimeException');
//
//        $signalsListener = new SignalsListener();
//        $signalsListener->setSignals(new Signals($signals));
//        $signalsListener->listen();
//        $pcntl_async_signals->disable();
//        $pcntl_signal->disable();
//    }

    public function testIntercept()
    {
        $signal = SIGINT;
        $signalsListener = new SignalsListener();
        $signalsListener->setSignals(new Signals([$signal]));

        $daemon = $this->createMock(DaemonInterface::class);

        $daemon->expects($this->once())
            ->method('update')
            ->with($signalsListener, $signal)
        ;

        $signalsListener->attach($daemon);
        $signalsListener->listen();

        posix_kill(getmypid(), $signal);
    }
}
