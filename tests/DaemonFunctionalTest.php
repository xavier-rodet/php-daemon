<?php

namespace Snailweb\Utils\Daemon\Tests;

use PHPUnit\Framework\TestCase;

class DaemonFunctionalTest extends TestCase
{
    use AccessProtected;

    // Need to manage process
    // Start process
    // Check if is running
    // Send stop / kill signals

    public function startProcess(string $scriptName)
    {
        $descriptorSpec = [
            0 => ['pipe', 'r'], // stdin is a pipe that the process will read from
            1 => ['file', dirname(__FILE__) . '/process-stdout-'.$scriptName.'.log', 'a'], // stdout is a file that the process will write in
            2 => ['file', dirname(__FILE__) . '/process-stderr-'.$scriptName.'.log', 'a'], // stderr is a file that the process will write in
        ];
        $processResource = proc_open("php " . dirname(__FILE__) . "/{$scriptName}.php", $descriptorSpec, $pipes);

        if (!is_resource($processResource)) {
            return null;
        }

        $process_details = proc_get_status($processResource);
        return $process_details['pid'];

//        $pid = shell_exec("php " . dirname(__FILE__) . "/{$scriptName}.php" . "> /dev/null 2>&1 & echo $!");
//        return intval($pid);
    }

    public function isProcessRunning(int $pid)
    {
//        return file_exists("/proc/{$pid}}");

        try {
            $result = shell_exec(sprintf('ps %d', $pid));
            if(count(preg_split("/\n/", $result)) > 2) {
                return true;
            }
        } catch(Exception $e) {}

        return false;
    }


    public function stopProcess(int $pid)
    {
        posix_kill($pid, SIGINT);

        $error = posix_get_last_error();
        $msg = posix_strerror($error);
    }

    public function killProcess(int $pid)
    {
        posix_kill($pid, SIGTERM);

        $error = posix_get_last_error();
        $msg = posix_strerror($error);
    }


    public function testRun()
    {
        $pid = $this->startProcess('runFoobar');

        $this->assertNotNull($pid, "pid is null");
        $this->assertTrue($this->isProcessRunning($pid));

        return $pid;
    }

//    /**
//     * @depends testRun
//     * @param int $pid
//     */
//    public function testStop(int $pid)
//    {
//        // IMPOSSIBLE TO RUN STOP/KILL COMMANDS AS TESTS ARE RUN FROM PHP-UNIT BINARY INSTEAD OF PHP
//        $this->stopProcess($pid);
//        $this->assertFalse($this->isProcessRunning($pid));
//    }

//    /**
//     * @depends testRun
//     * @param int $pid
//     */
//    public function testKill(int $pid)
//    {
//        $this->killProcess($pid);
////        sleep(1);
//        $this->assertFalse($this->isProcessRunning($pid));
//    }

//    public function testUpdateRunCondition()
//    {
//        $this->daemon->run(false);
//    }

//    public function testRun()
//    {
//        $this->daemon->run(false);
//    }
}
