<?php

# Event logging system for debugging

# Choose a good location on your computer system
define ("LOGPATH",
    "C:\\Users\\Dell\\Desktop\\enlsoftware\\srvdebug.log");
# Turn logging on or off
define ("LOGENABLED", False);

function LogWrite ($message) {
    if (LOGENABLED) {
        if ($logFileHandle = fopen (LOGPATH, "a")) {
            # Time in Poland
            $msgTime = date ('D H:i:s', time () + 7200);
            fputs ($logFileHandle, "$msgTime | $message\n");
            fclose ($logFileHandle);
        }
    }
    # The file is being opened and closed with every message
    # to make sure everything gets saved before and kind of crash.
}

?>

