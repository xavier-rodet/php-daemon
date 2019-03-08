<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Tests\Signals\Listener;

use PHPUnit\Framework\TestCase;
use Snailweb\Daemon\Signals\Listener\SignalsListener;
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

    public function testListen()
    {
    }

    public function testGetInterceptedSignal()
    {
    }
}
