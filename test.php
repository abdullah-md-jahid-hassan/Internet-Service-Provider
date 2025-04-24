<?php
function compareDateWithToday($date) {
    $givenDate = strtotime($date);
    $today = strtotime(date('Y-m-d'));

    if ($givenDate > $today) {
        return "On time.";
    } elseif ($givenDate < $today) {
        return "Late.";
    } else {
        return "The given date is today.";
    }
}

// Example usage
echo compareDateWithToday('202-12-25');
?>