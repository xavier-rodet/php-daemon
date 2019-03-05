<?php


namespace Snailweb\Daemon\Processor;


interface ProcessorInterface
{
    public function setUp() : void;

    public function tearDown() : void;

    public function process() : void;
}