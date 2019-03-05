<?php

declare(strict_types=1);

include dirname(__FILE__).'/../../vendor/autoload.php';

use Snailweb\Daemon\Tests\FoobarDaemon;

$foobar = new FoobarDaemon();
$foobar->run();
