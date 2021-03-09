<?php

ini_set('display_errors', 1);
ini_set('ignore_repeated_errors', true);
ini_set('display_errors', false);
ini_set('log_errors', true);
ini_set('error_log', '../logs/errors.log'); // Logging file path
error_reporting(E_ALL);

require '../vendor/autoload.php';
require '../config/config.php';

if (empty($config)) {
    die('Config not found!');
}

use Api\{CurlRequest, Auth, PostCollection};
use Storage\DbConnection;
use Reports\DbReports;

try {
    $Storage = DbConnection::getInstance($config['database']);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();

} catch (Exception $e) {
    echo "Configuration error: " . $e->getMessage();
}

$request = new CurlRequest();

$auth_data = [
    'client_id' => $config['api']['client_id'],
    'email' => $config['email'],
    'name' => $config['name'],
    'url' => $config['api']['register_endpoint']
];

$auth = new Auth($request, $auth_data);
$auth->registerToken();

$collection = new PostCollection($request, $auth, $Storage, $config['api']['posts_endpoint']);
$collection->fetchAll();

$reports = new DbReports($Storage);
$results = [
    'average_length' => $reports->getAverageLengthsByMonth(),
    'longest_posts' => $reports->getLongestPostsByMonth(),
    'posts_by_week' => $reports->getPostsNumberByWeek(),
    'posts_by_user_month' => $reports->getAveragePostsPerUserPerMonth(),
];
echo json_encode($results);
