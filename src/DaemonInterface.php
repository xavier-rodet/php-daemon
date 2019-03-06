<?php

declare(strict_types=1);

namespace Snailweb\Daemon;

use Snailweb\Daemon\Processor\ProcessorInterface;
use Snailweb\Daemon\Strategy\StrategyInterface;

interface DaemonInterface extends \SplObserver
{
    public function setOptions(array $options): void;

    public function getOptions(): array;

    public function getProcessor(): ProcessorInterface;

    public function setProcessor(ProcessorInterface $processor): void;

    public function getStrategy(): StrategyInterface;

    public function setStrategy(StrategyInterface $strategy): void;

    public function run(?StrategyInterface $strategy): void;
}
