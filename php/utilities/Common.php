<?php

// function ReturnCurrentPath()
// {
//     $path = "";
//     $req = $_SERVER['REDIRECT_URL'];
//     switch ($req) {
//         case "/":
//             $path = "/index";
//             break;
//         default:
//             $path = $req;
//             break;
//     }
//     return $path;
// }

function IncludeCSS($file)
{
    if (file_exists(__DIR__ . "/../../pub" . $file)) {
        $v = filemtime(__DIR__ . "/../../pub" . $file);
        echo "<link rel='stylesheet' type='text/css' href='//movies.spockfamily.net" . $file . "?v=" . $v . "' />\n";
    }
}
function IncludeJS($file)
{
    if (file_exists(__DIR__ . "/../../pub" . $file)) {
        $v = filemtime(__DIR__ . "/../../pub" . $file);
        echo "<script src='//movies.spockfamily.net" . $file . "?v=" . $v . "'></script>\n";
    }
}

function ReturnUserIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }

    return $ip;
}

function FormatMoney($val)
{
    $formatter_us = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
    return $formatter_us->formatCurrency($val, 'USD') . PHP_EOL;
}

function readableBytes($bytes) {
    $i = floor(log($bytes) / log(1024));
    $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    // Use sprintf for formatting the number to two decimal places
    $formattedSize = sprintf('%.02F', $bytes / pow(1024, $i));
    return $formattedSize * 1 . ' ' . $sizes[$i]; // Multiplies by 1 to remove trailing zeros from sprintf where appropriate
}