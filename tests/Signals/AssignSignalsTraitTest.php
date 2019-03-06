<?php

namespace Snailweb\Daemon\Tests\Signals;

use PHPUnit\Framework\TestCase;
use Snailweb\Daemon\Signals\AssignSignalsTrait;
use Snailweb\Daemon\Signals\SignalsInterface;

class AssignSignalsTraitTest extends TestCase
{
    public function testAccessorSignals()
    {
        $assignSignalsTrait = $this->getMockForTrait(AssignSignalsTrait::class);

        $signals = $this->createMock(SignalsInterface::class);
        $assignSignalsTrait->setSignals($signals);

        $this->assertSame($signals, $assignSignalsTrait->getSignals());
    }
}
