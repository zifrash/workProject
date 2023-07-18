<?php

phpinfo();

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
