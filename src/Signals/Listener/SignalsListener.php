<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Signals\Listener;

use Snailweb\Daemon\Daemon;
use Snailweb\Daemon\Signals\SignalsAccessorTrait;

final class SignalsListener implements SignalsListenerInterface
{
    use SignalsAccessorTrait;

    private $daemons = [];

    public function listen(): void
    {
        pcntl_async_signals(true);

        foreach ($this->signals as $signal) {
            if (!pcntl_signal($signal, [$this, 'intercept'])) {
                throw new \RuntimeException(sprintf('Failed listening to signal %d', $signal));
            }
        }
    }

    /**
     * Attach an SplObserver.
     *
     * @see https://php.net/manual/en/splsubject.attach.php
     *
     * @param \SplObserver $daemon
     * @since 5.1.0
     */
    public function attach(\SplObserver $daemon): void
    {
        $this->daemons[] = $daemon;
    }

    /**
     * Detach an observer.
     *
     * @see https://php.net/manual/en/splsubject.detach.php
     *
     * @param \SplObserver $daemon
     * @since 5.1.0
     */
    public function detach(\SplObserver $daemon): void
    {
        if (is_int($key = array_search($daemon, $this->daemons, true))) {
            unset($this->daemons[$key]);
        }
    }

    /**
     * Notify an observer.
     *
     * @see https://php.net/manual/en/splsubject.notify.php
     * @since 5.1.0
     * @param int|null $signal
     */
    public function notify(int $signal = null): void
    {
        foreach ($this->daemons as $daemon) {
            $daemon->update($this, $signal);
        }
    }

    private function intercept(int $signal): void
    {
        if (in_array($signal, $this->signals)) {
            $this->notify($signal);
        }
    }
}
