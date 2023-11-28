<?php

require __DIR__ . '/vendor/autoload.php';

// EFS location of wordpress root
$wpDir = '/mnt/root/wordpress';

$_SERVER['HTTPS'] = 'on';
$_SERVER['DOCUMENT_ROOT'] = $wpDir;
chdir($wpDir);

$extension_map = array(
    "css" => "text/css",
    "js" => "application/javascript",
    "png" => "image/png",
    "jpeg" => "image/jpeg",
    "jpg" => "image/jpeg",
    "gif" => "image/gif",
    "svg" => "image/svg+xml",
    "woff" => "font/woff",
    "woff2" => "font/woff2",
    "ttf" => "font/ttf",
);

$request_uri = explode("?", $_SERVER['REQUEST_URI']);
$local_file_path = $_SERVER['DOCUMENT_ROOT'] . $request_uri[0];

if ( $local_file_path == __FILE__ ) {
    http_response_code(400);
    die('The handler can not be called directly.');
}

$split = explode(".", $local_file_path);
$extension = end($split);
$mapped_type = $extension_map[$extension];

if ( $mapped_type && file_exists( $local_file_path ) ) {
    header("Content-Type: {$mapped_type}");
    readfile($local_file_path);
} elseif ( $extension == "php" && file_exists( $local_file_path ) ) {
    $_SERVER['SCRIPT_FILENAME'] = $local_file_path;
    header("X-ExecFile: {$local_file_path}");
    require( $local_file_path );
} elseif ( substr($local_file_path, -1) == "/" && file_exists( $local_file_path . "index.php" ) ) {
    $exec_file_path = $local_file_path . "index.php";
    $_SERVER['SCRIPT_FILENAME'] = $exec_file_path;
    header("X-ExecFile: {$exec_file_path}");
    require( $exec_file_path );
} else {
    $exec_file_path = getcwd() . '/index.php';
    $_SERVER['SCRIPT_FILENAME'] = $exec_file_path;
    header("X-ExecFile: {$exec_file_path}");
    require( $exec_file_path );
}