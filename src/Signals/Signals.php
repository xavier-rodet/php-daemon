<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Signals;

final class Signals implements SignalsInterface
{
    private $key;
    private static $acceptedSignals = [SIGINT, SIGTERM];
    private $signals = [];

    public function __construct(array $signals)
    {
        foreach ($signals as $signal) {
            $this->add($signal);
        }

        $this->key = 0;
    }

    public function add(int $signal): void
    {
        if (!in_array($signal, self::$acceptedSignals)) {
            throw new \InvalidArgumentException(sprintf('The signal %d is invalid (expected: %s)', $signal, implode(', ', self::$acceptedSignals)));
        }

        if (in_array($signal, $this->signals)) {
            throw new \InvalidArgumentException(sprintf('The signal %d is already added', $signal));
        }

        $this->signals[] = $signal;
    }

    /**
     * Return the current element.
     *
     * @see https://php.net/manual/en/iterator.current.php
     *
     * @return mixed can return any type
     *
     * @since 5.0.0
     */
    public function current()
    {
        return $this->signals[$this->key];
    }

    /**
     * Move forward to next element.
     *
     * @see https://php.net/manual/en/iterator.next.php
     * @since 5.0.0
     */
    public function next(): void
    {
        ++$this->key;
    }

    /**
     * Return the key of the current element.
     *
     * @see https://php.net/manual/en/iterator.key.php
     *
     * @return mixed scalar on success, or null on failure
     *
     * @since 5.0.0
     */
    public function key(): int
    {
        return $this->key;
    }

    /**
     * Checks if current position is valid.
     *
     * @see https://php.net/manual/en/iterator.valid.php
     *
     * @return bool The return value will be casted to boolean and then evaluated.
     *              Returns true on success or false on failure.
     *
     * @since 5.0.0
     */
    public function valid(): bool
    {
        return isset($this->signals[$this->key]);
    }

    /**
     * Rewind the Iterator to the first element.
     *
     * @see https://php.net/manual/en/iterator.rewind.php
     * @since 5.0.0
     */
    public function rewind(): void
    {
        $this->key = 0;
    }
}
