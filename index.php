<?php

require_once __DIR__.'/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'user', 'password');
$channel = $connection->channel();

$channel->queue_declare('hello', false, false, false, false);

$msg = new AMQPMessage('Hello World!');
$channel->basic_publish($msg, '', 'hello');

echo " [x] Sent 'Hello World!'\n";

// phpinfo();

//$redis = new Redis([
//    'host' => 'redis',
//    'auth' => 'password'
//]);
//
//$redis->set('product-name:1', 'аспирин');
//$redis->set('product-name:2', 'фенибут');
//$redis->set('product-name:3', 'но-шпа');
//$redis->set('product-name:4', 'уголь активированный');
//
//$productName = $redis->keys('product-name:*');
//
//echo "<pre>";
//var_dump($productName);

//$host= 'db';
//$db = 'database';
//$user = 'user';
//$password = 'password';
//
//try {
//    $dsn = "pgsql:host=$host;port=5432;dbname=$db;";
//    // make a database connection
//    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
//
//    if ($pdo) {
//        echo "Connected to the $db database successfully!";
//    }
//} catch (PDOException $e) {
//    die($e->getMessage());
//} finally {
//    if ($pdo) {
//        $pdo = null;
//    }
//}
