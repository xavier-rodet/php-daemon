<?php

declare(strict_types=1);

namespace Snailweb\Daemon;

use Prophecy\Exception\InvalidArgumentException;
use Snailweb\Daemon\Processor\ProcessorInterface;
use Snailweb\Daemon\Signals\Listener\SignalsListener;
use Snailweb\Daemon\Signals\Manager\SignalsManager;
use Snailweb\Daemon\Strategy\AbstractStrategy;
use Snailweb\Daemon\Strategy\Forever;
use Snailweb\Daemon\Strategy\StrategyInterface;
use SplSubject;

final class Daemon implements DaemonInterface
{
    private $options = [];
    private $runStartTime;
    private $processStartTime;

    private $processor;
    private $signalsManager;
    private $strategy;


    public function __construct(ProcessorInterface $processor, SignalsManager $signalsManager)
    {
        $this->options = $this->getDefaultOptions();
        $this->setStrategy(new Forever());
        $this->processor = $processor;
        $this->signalsManager = $signalsManager;
        $this->signalsManager->listener()->attach($this);
    }

    public function run(AbstractStrategy $strategy = null) : void
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


    private function getDefaultOptions(): array
    {
        return [
            'run_ttl' => 86400, // seconds (1 day)
            'run_memory_limit' => 128, // MB
            'process_min_exec_time' => 100, // milliseconds
        ];
    }

    /**
     * @param array $options
     */
    public function assignOptions(array $options): void
    {
        $this->options = array_merge($this->options, $options);
    }

    private function getOption(string $name)
    {
        if(!isset($this->options[$name])) {
            throw new InvalidArgumentException(sprintf("%s option doesn't exist", $name));
        }

        return $this->options[$name];
    }

    /**
     * @return ProcessorInterface
     */
    public function getProcessor(): ProcessorInterface
    {
        return $this->processor;
    }

    /**
     * @param ProcessorInterface $processor
     */
    public function setProcessor(ProcessorInterface $processor): void
    {
        $this->processor = $processor;
    }

    /**
     * @return StrategyInterface
     */
    public function getStrategy() : StrategyInterface
    {
        return $this->strategy;
    }

    /**
     * @param StrategyInterface $strategy
     */
    public function setStrategy(StrategyInterface $strategy): void
    {
        $this->strategy = $strategy;
    }


    private function initRun(?AbstractStrategy $strategy) : void
    {
        $this->runStartTime = time();
        $this->signalsManager->listener()->listen();

        if(!is_null($strategy)) {
            $this->setStrategy($strategy);
        }
    }

    private function initProcess() : void
    {
        $this->processStartTime = intval(microtime(true) * 1000);
    }

    private function assureProcessMinExecTime() : void
    {
        $process_min_exec_time = $this->getOption('process_min_exec_time');
        $process_exec_time = intval(microtime(true) * 1000) - $this->processStartTime;

        if ($process_exec_time < $process_min_exec_time) {
            usleep(($process_min_exec_time - $process_exec_time) * 1000);
        }
    }

    private function shouldRestart(): bool
    {
        return (
            $this->hasReachedTTL($this->getOption('run_ttl'))
            || $this->hasReachedMemoryLimit($this->getOption('run_memory_limit'))
        );
    }

    private function runTime() : int
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

    private function stop() : void
    {
        exit();
    }

    /**
     * Receive update from subject
     * @link https://php.net/manual/en/splobserver.update.php
     * @param SplSubject $subject <p>
     * The <b>SplSubject</b> notifying the observer of an update.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function update(SplSubject $subject) : void
    {
        if($subject instanceof SignalsListener) {

            $this->signalsManager->handler()->handle($subject->signal(), $this);
        }
    }
}
