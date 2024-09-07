<?php

// POSIX-compatible implementations
// See osdependent.php

function osdep_GetUsername () {
    return exec ("whoami");
}

function osdep_ProcessRunning ($pid) {
    return posix_kill ($pid, 0);
}

function osdep_KillProcess ($pid) {
    posix_kill ($pid, 15);
}

?>

