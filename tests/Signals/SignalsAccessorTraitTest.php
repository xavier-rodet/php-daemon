<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Tests\Signals;

use PHPUnit\Framework\TestCase;
use Snailweb\Daemon\Signals\SignalsAccessorTrait;
use Snailweb\Daemon\Signals\SignalsInterface;

/**
 * @internal
 */
class SignalsAccessorTraitTest extends TestCase
{
    public function testAccessorSignals()
    {
        $assignSignalsTrait = $this->getMockForTrait(SignalsAccessorTrait::class);

        $signals = $this->createMock(SignalsInterface::class);
        $assignSignalsTrait->setSignals($signals);

        $this->assertSame($signals, $assignSignalsTrait->getSignals());
    }
}
