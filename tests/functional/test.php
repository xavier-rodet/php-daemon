<?php

declare(strict_types=1);

// MANUAL TESTS
$scriptName = 'runFoobar';

$descriptorSpec = [
    0 => ['pipe', 'r'], // stdin is a pipe that the process will read from
    1 => ['file', './process-stdout-'.$scriptName.'.log', 'a'], // stdout is a file that the process will write in
    2 => ['file', './process-stderr-'.$scriptName.'.log', 'a'], // stderr is a file that the process will write in
];
$processResource = proc_open('php '.dirname(__FILE__)."/{$scriptName}.php", $descriptorSpec, $pipes);

if (!is_resource($processResource)) {
    echo 'fail';
}

$process_details = proc_get_status($processResource);
$pid = $process_details['pid'];

//$pid = shell_exec("php " . dirname(__FILE__) . "/{$scriptName}.php" . "> /dev/null 2>&1 & echo $!");

echo $pid;

function isRunning($pid)
{
    try {
        $result = shell_exec(sprintf('ps %d', $pid));
        if (count(preg_split("/\n/", $result)) > 2) {
            return true;
        }
    } catch (Exception $e) {
    }

    return false;
}

//sleep(1);
//if(file_exists ("/proc/{$pid}}")) {
if (isRunning($pid)) {
    echo 'true';
} else {
    echo 'false';
}

//$pid = 25;
// WORKS HERE, BUT NOT IN FUNCTIONNAL TEST : because it's execute from php-unit binary instead of php
posix_kill($pid, SIGINT);

$error = posix_get_last_error();
$msg = posix_strerror($error);
echo $msg;

/// GOO TEST :
///
/// IF I RUN THIS SCRIPT WITH ONLY PROCESS CREATION
/// THEN I RUN IT AGAIN WITH ONLY KILL PROCESS WITH PREVIOUS PROCESS ID : IT WORKS !!
