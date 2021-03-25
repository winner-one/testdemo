<?php

if (!function_exists('matchLatLng')) {
    function matchLatLng($latlng) {
        $match = "/^\d{1,3}\.\d{1,30}$/";
        return preg_match($match, $latlng) ? $latlng : 0;
    }
}


if (!function_exists('getDistanceBuilder')) {
    function getDistanceBuilder($lat, $lng) {
        return "ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN((". matchLatLng($lat) . " * PI() / 180 - latitude * PI() / 180) / 2), 2) + COS(". matchLatLng($lat). " * PI() / 180) * COS(latitude * PI() / 180) * POW(SIN((". matchLatLng($lng). " * PI() / 180 - longitude * PI() / 180) / 2), 2))) * 1000) AS distance";
    }
}