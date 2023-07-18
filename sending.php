<?php

require_once __DIR__.'/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'user', 'password');
$channel = $connection->channel();

$channel->queue_declare('hello', false, true, false, false);

$msgText = $argv[1] ?? 'Hello World!';

$msg = new AMQPMessage($msgText, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
$channel->basic_publish($msg, '', 'hello');

echo " [x] Sent '{$msgText}'\n";

$channel->close();
$connection->close();
