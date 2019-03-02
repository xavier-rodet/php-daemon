<?php

declare(strict_types=1);

namespace Snailweb\Utils;

abstract class Daemon
{
    protected $options = [];
    protected $runStartTime;
    protected $processStartTime;
    private $runCondition;

    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->getDefaultOptions(), $options);
    }

    final public function run(bool $forever = true, int $ttl = 0)
    {
        $this->initRun($forever, $ttl);
        $this->setUp();

        while ($this->runCondition->__invoke()) {
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

    final protected function initRun(bool $forever, int $ttl)
    {
        $this->runStartTime = time();
        $this->listenToSignals();
        $this->initRunCondition($forever, $ttl);
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

    /**
     * Initialize run condition depending on run parameters.
     * This method should be override if you need to handle specific run conditions.
     *
     * @param bool $forever
     * @param int  $ttl
     */
    protected function initRunCondition(bool $forever, int $ttl)
    {
        if ($forever) {
            $runCondition = function () { return true; };
        } else {
            $runCondition = function () use ($ttl) { return time() < $this->runStartTime + $ttl; };
        }

        $this->updateRunCondition($runCondition);
    }

    final protected function updateRunCondition(\Closure $runCondition)
    {
        $this->runCondition = $runCondition;
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
        $runCondition = function() { return false; };
        $this->updateRunCondition($runCondition);
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
