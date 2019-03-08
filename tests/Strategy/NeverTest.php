<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Tests\Strategy;

use PHPUnit\Framework\TestCase;
use Snailweb\Daemon\Strategy\Never;

/**
 * @internal
 */
class NeverTest extends TestCase
{
    public function testStrategy()
    {
        $strategy = new Never();
        $this->assertFalse($strategy->test());
    }
}
