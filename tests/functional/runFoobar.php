<?php

include dirname(__FILE__) . '/../../vendor/autoload.php';

use Snailweb\Utils\Daemon\Tests\FoobarDaemon;

$foobar = new FoobarDaemon();
$foobar->run();