<?php
function format_time($date)
{
    $publicationTime = 0;
    if (!is_numeric(($date))) {
        $publicationTime = strtotime($date);
    } else {
        $publicationTime = (int)$publicationTime;
    }
    $now = time();
    $diff = $now - $publicationTime;

    if ($diff < 60) {
        return "щойно";
    } elseif ($diff < 3600) { // менше години
        $minutes = floor($diff / 60);
        return $minutes . " хв тому";
    } elseif ($diff < 86400) { // менше дня
        $hours = floor($diff / 3600);
        return $hours . " год тому";
    } elseif ($diff < 604800) { // менше тижня
        $days = floor($diff / 86400);
        return $days . " дн тому";
    } else {
        return date('d.m.Y H:i', $publicationTime);
    }
}
