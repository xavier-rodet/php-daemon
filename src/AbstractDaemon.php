<?php

declare(strict_types=1);

namespace Snailweb\Daemon;

use Snailweb\Daemon\Strategy\AbstractStrategy;
use Snailweb\Daemon\Strategy\Forever;
use Snailweb\Daemon\Strategy\Never;

abstract class AbstractDaemon
{
    protected $options = [];
    protected $runStartTime;
    protected $processStartTime;
    private $strategy;

    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->getDefaultOptions(), $options);
    }

    final public function run(?AbstractStrategy $strategy = null)
    {
        $this->initRun($strategy);
        $this->setUp();

        while ($this->strategy->test()) {
            $this->initProcess();
            $this->process();

            if ($this->shouldRestart()) {
                $this->stop();
            }

            $this->assureProcessMinExecTime();
        }
        $this->tearDown();
    }

    protected function getDefaultOptions(): array
    {
        return [
            'run_ttl' => 86400, // 1 Day
            'run_memory_limit' => 128, // MB
            'process_min_exec_time' => 100, // 100ms
        ];
    }

    final protected function initRun(?AbstractStrategy $strategy)
    {
        $this->runStartTime = time();
        $this->listenToSignals();

        if(is_null($strategy)) {
            $strategy = new Forever();
        }
        $this->updateStrategy($strategy);
    }

    final protected function initProcess()
    {
        $this->processStartTime = intval(microtime(true) * 1000);
    }

    final protected function assureProcessMinExecTime()
    {
        $process_min_exec_time = $this->options['process_min_exec_time'];
        $process_exec_time = intval(microtime(true) * 1000) - $this->processStartTime;

        if ($process_exec_time < $process_min_exec_time) {
            usleep(($process_min_exec_time - $process_exec_time) * 1000);
        }
    }

    final protected function shouldRestart(): bool
    {
        if (
            $this->hasReachedTTL($this->options['run_ttl'])
            || $this->hasReachedMemoryLimit($this->options['run_memory_limit'])
        ) {
            return true;
        }

        return false;
    }

    final protected function listenToSignals()
    {
        pcntl_async_signals(true);

        foreach ([SIGINT, SIGTERM] as $signal) {
            if (!pcntl_signal($signal, [$this, 'handleSignal'])) {
                throw new \RuntimeException(sprintf('Failed listening to signal %d', $signal));
            }
        }
    }

    final protected function handleSignal(int $signal)
    {
        switch ($signal) {
            case SIGINT:
                $this->handleSoftExit();
                break;

            case SIGTERM:
                $this->handleHardExit();
                break;
        }
    }

    final protected function updateStrategy(AbstractStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    final protected function hasReachedTTL(int $timeout): bool
    {
        return time() - $this->runStartTime >= $timeout;
    }

    final protected function hasReachedMemoryLimit(int $memoryLimit): bool
    {
        return (memory_get_usage() / 1024 / 1024) >= $memoryLimit;
    }

    /**
     * default "soft exit" signal handling: stop process loop on next iteration
     */
    protected function handleSoftExit()
    {
        $this->updateStrategy(new Never());
    }


    /**
     * default "hard exit" signal handling : immediately stop daemon
     */
    protected function handleHardExit()
    {
        $this->stop();
    }

    final protected function stop()
    {
        exit();
    }

    abstract protected function setUp();

    abstract protected function tearDown();

    abstract protected function process();
}
