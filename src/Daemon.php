<?php

declare(strict_types=1);

namespace Snailweb\Daemon;

use Prophecy\Exception\InvalidArgumentException;
use Snailweb\Daemon\Processor\ProcessorInterface;
use Snailweb\Daemon\Signals\Handler\DefaultSignalHandler;
use Snailweb\Daemon\Signals\Listener\SignalsListener;
use Snailweb\Daemon\Signals\Listener\SignalsListenerInterface;
use Snailweb\Daemon\Signals\Manager\SignalsManager;
use Snailweb\Daemon\Signals\Manager\SignalsManagerInterface;
use Snailweb\Daemon\Signals\Signals;
use Snailweb\Daemon\Strategy\Forever;
use Snailweb\Daemon\Strategy\StrategyInterface;

final class Daemon implements DaemonInterface
{
    private $options = [];
    private $runStartTime;
    private $processStartTime;

    private $processor;
    private $signalsManager;
    private $strategy;

    public function __construct(ProcessorInterface $processor, SignalsManagerInterface $signalsManager = null)
    {
        $this->options = $this->getDefaultOptions();

        // Default strategy (can be override when calling run())
        $this->setStrategy(new Forever());

        $this->processor = $processor;

        // Default signals manager won't affect default UNIX signal system
        if (is_null($signalsManager)) {
            $signals = new Signals(); // No signals
            $signalsListener = new SignalsListener();
            $signalsHandler = new DefaultSignalHandler();
            $signalsManager = new SignalsManager($signals, $signalsListener, $signalsHandler);
        }

        $this->signalsManager = $signalsManager;
        // We will observe the SignalsListener
        $this->signalsManager->getListener()->attach($this);
    }

    public function run(StrategyInterface $strategy = null): void
    {
        $this->initRun($strategy);
        $this->getProcessor()->setUp();

        while ($this->strategy->test()) {
            $this->initProcess();
            $this->getProcessor()->process();

            if ($this->shouldRestart()) {
                $this->stop();
            }

            $this->assureProcessMinExecTime();
        }
        $this->getProcessor()->tearDown();
    }

    public function setOptions(array $options): void
    {
        foreach ($options as $name => $value) {
            $this->setOption($name, $value);
        }
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getProcessor(): ProcessorInterface
    {
        return $this->processor;
    }

    public function setProcessor(ProcessorInterface $processor): void
    {
        $this->processor = $processor;
    }

    public function getStrategy(): StrategyInterface
    {
        return $this->strategy;
    }

    public function setStrategy(StrategyInterface $strategy): void
    {
        $this->strategy = $strategy;
    }

    /**
     * Receive update from subject.
     *
     * @see https://php.net/manual/en/splobserver.update.php
     *
     * @param \SplSubject $signalListener
     * @param null|int    $signal
     *
     * @since 5.1.0
     */
    public function update(\SplSubject $signalListener, int $signal = null): void
    {
        // When SignalsListener notify us of a new signal, we pass it to SignalsHandler
        if ($signalListener instanceof SignalsListenerInterface) {
            $this->signalsManager->getHandler()->handle($signal, $this);
        }
    }

    public function stop(): void
    {
        exit();
    }

    private function getDefaultOptions(): array
    {
        return [
            'run_ttl' => 86400, // seconds (1 day)
            'run_memory_limit' => 128, // MB
            'process_min_exec_time' => 100, // milliseconds
        ];
    }

    private function setOption(string $name, $value)
    {
        if (!isset($this->options[$name])) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s option is invalid (expected : %s)',
                    $name,
                    implode(
                        ', ',
                        array_keys($this->options)
                    )
                )
            );
        }

        $this->options[$name] = $value;
    }

    private function initRun(?StrategyInterface $strategy): void
    {
        $this->runStartTime = time();
        $this->signalsManager->getListener()->listen();

        if (!is_null($strategy)) {
            $this->setStrategy($strategy);
        }
    }

    private function initProcess(): void
    {
        $this->processStartTime = intval(microtime(true) * 1000);
    }

    private function assureProcessMinExecTime(): void
    {
        $process_min_exec_time = $this->options['process_min_exec_time'];
        $process_exec_time = intval(microtime(true) * 1000) - $this->processStartTime;

        if ($process_exec_time < $process_min_exec_time) {
            usleep(($process_min_exec_time - $process_exec_time) * 1000);
        }
    }

    private function shouldRestart(): bool
    {
        return
            $this->hasReachedTTL($this->options['run_ttl'])
            || $this->hasReachedMemoryLimit($this->options['run_memory_limit'])
        ;
    }

    private function runTime(): int
    {
        return time() - $this->runStartTime;
    }

    private function hasReachedTTL(int $timeout): bool
    {
        return $this->runTime() >= $timeout;
    }

    private function hasReachedMemoryLimit(int $memoryLimit): bool
    {
        return (memory_get_usage() / 1024 / 1024) >= $memoryLimit;
    }
}
