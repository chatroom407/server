<?php

// Microsoft(R) Windows-compatible implementations
// See osdependent.php

function osdep_ProcessExists ($searchedPID) {
	$tasklistPipe = popen ("tasklist", "r");
	
	$lineCount = 1;
	$foundFlag = false;
	
	while ($line = fgets ($tasklistPipe)) {
		if ($lineCount > 3) {
			// The first 3 lines of tasklist output
			// are decorational
		
			// Extract the PID column, trim it because it's
			// right-aligned, and convert to integer form
			$listedPID = intval (ltrim (
				substr ($line, 26, 8)
			));
			
			if ($listedPID == $searchedPID) {
				$foundFlag = true;
				// Don't return, we have to close
				// the opened pipe.
				break;
			}
		}
		++$lineCount;
	}	
	pclose ($tasklistPipe);

	return $foundFlag;
}

?>

