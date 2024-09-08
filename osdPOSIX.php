<?php

// POSIX-compatible implementations
// See osdependent.php

function osdep_GetUsername () {
    return exec ("whoami");
}

function osdep_ProcessRunning($pid) {
    //posix_kill ($pid, 0);
    $cmd = "ps -p $pid -o pid=";
    $output = [];
    exec($cmd, $output);
    
    return !empty($output[0]);
}

function osdep_KillProcess ($pid) {
    //posix_kill ($pid, 15);
    $cmd = "kill $pid ";
    $output = [];
    exec($cmd, $output);
}

function osdep_findProcess($name) {
    $output = shell_exec("ps aux | grep $name | grep -v grep");
    if (!$output) {
        return null;  
    }

    $lines = explode("\n", trim($output));
    $pids = [];
    foreach ($lines as $line) {
        $columns = preg_split('/\s+/', $line);
        if (isset($columns[1])) {
            $pids[] = $columns[1];  
        }
    }

    return count($pids) === 1 ? $pids[0] : $pids;
}

?>

