<?php

if (!function_exists('parsePeriodToSeconds')) {
    function parsePeriodToSeconds(string $period): int
    {
        $parts = explode(':', $period);

        switch (count($parts)) {
            case 1:
                return (int) $parts[0];
                break;
            case 2:
                return (int) ($parts[0] * 60) + (int) $parts[1];
                break;
            case 3:
                return (int) ($parts[0] * 3600) + (int) ($parts[1] * 60) + (int) $parts[2];
                break;
            default:
                return 3600 * 24;
                break;
        }
    }
}

