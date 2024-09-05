<?php

class ValidateMsg {
    
    public function validateLength($str, $maxLength) {
        $trimmedStr = substr($str, 0, $maxLength);
        return $str === $trimmedStr;
    }

    public function validateStartEnd($str, $start, $end) {
        $startsWith = strpos($str, $start) === 0;
        $endsWith = substr($str, -strlen($end)) === $end;

        return $startsWith && $endsWith;
    }
}
?>
