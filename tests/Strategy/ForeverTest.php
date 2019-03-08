<?php

declare(strict_types=1);

namespace Snailweb\Daemon\Tests\Strategy;

use PHPUnit\Framework\TestCase;
use Snailweb\Daemon\Strategy\Forever;

/**
 * @internal
 */
class ForeverTest extends TestCase
{
    public function testStrategy()
    {
        $strategy = new Forever();
        $this->assertTrue($strategy->test());
    }
}
