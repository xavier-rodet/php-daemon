<?php


namespace Snailweb\Daemon\Signals\Listener;


use Snailweb\Daemon\Signals\AssignSignalsTrait;
use SplObserver;

final class SignalsListener implements SignalsListenerInterface
{
    use AssignSignalsTrait;

    private $observers = [];
    private $signal;

    public function listen(): void
    {
        pcntl_async_signals(true);

        foreach ($this->signals as $signal) {
            if (!pcntl_signal($signal, [$this, 'intercept'])) {
                throw new \RuntimeException(sprintf('Failed listening to signal %d', $signal));
            }
        }
    }

    private function intercept(int $signal): void
    {
        if(in_array($signal, $this->signals)) {
            $this->signal = $signal;
            $this->notify();
        }
    }

    public function getSignal(): int
    {
        return $this->signal;
    }

    /**
     * Attach an SplObserver
     * @link https://php.net/manual/en/splsubject.attach.php
     * @param SplObserver $observer <p>
     * The <b>SplObserver</b> to attach.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function attach(SplObserver $observer) : void
    {
        if(!in_array($observer, $this->observers)) {
            array_push($this->observers, $observer);
        }
    }

    /**
     * Detach an observer
     * @link https://php.net/manual/en/splsubject.detach.php
     * @param SplObserver $observer <p>
     * The <b>SplObserver</b> to detach.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function detach(SplObserver $observer) : void
    {
        if($key = array_search($observer, $this->observers)) {
            unset($this->observers[$key]);
        }
    }

    /**
     * Notify an observer
     * @link https://php.net/manual/en/splsubject.notify.php
     * @return void
     * @since 5.1.0
     */
    public function notify() : void
    {
        foreach($this->observers as $observer){
            $observer->update($this);
        }
    }
}