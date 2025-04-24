<?php
namespace general_purpose_functions;

/**
 * Function first_word(string $string): string
 * return the first word when find "_" or " "
 */
function first_word(string $string): string {
    $result = "";
    foreach (str_split($string) as $s) {
        if ($s === "_" || $s === " ") {
            break;
        }
        $result .= $s;
    }
    
    if ($result === "") {
        return "";
    }
    
    return ucfirst(strtolower($result));
}