<?php

// Functions that wrap all OS-dependent operations
// All of them can have multiple operations for every supported operating
// system, placed in a separate file for each supported OS.
// They all have the osdep_* prefix

if (PHP_OS_FAMILY == 'Windows') {
  // Windows
  include 'osdWindows.php';
} else if (function_exists ('posix_kill')) {
  // All POSIX-compatible systems
  include 'osdPOSIX.php';
}

?>

