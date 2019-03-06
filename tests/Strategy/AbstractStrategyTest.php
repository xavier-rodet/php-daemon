<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Tests\Strategy;

use PHPUnit\Framework\TestCase;
use Snailweb\Daemon\Strategy\AbstractStrategy;
use Snailweb\Daemon\Strategy\StrategyInterface;

class AbstractStrategyTest extends TestCase
{
    public function testConstruct()
    {
        $strategy = $this->getMockBuilder(AbstractStrategy::class)
            ->disableOriginalConstructor()
            ->setMethods(['buildCondition'])
            ->getMockForAbstractClass()
        ;

        $strategy->expects($this->once())
            ->method('buildCondition')
            ->willReturn(function () {
                return true;
            })
        ;
        $strategy->__construct();

        return $strategy;
    }

    /**
     * @depends testConstruct
     *
     * @param StrategyInterface $strategy
     */
    public function testTest(StrategyInterface $strategy)
    {
        $strategy->expects($this->once())
            ->method('initialize')
        ;

        // Call multiple tests to ensure initialize() is called only once
        $strategy->test();
        $strategy->test();

        $this->assertTrue($strategy->test());
    }
}
