<?php

require_once __DIR__.'/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'user', 'password');
$channel = $connection->channel();

$channel->queue_declare('hello', false, true, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$channel->basic_consume('hello', '', false, true, false, false, function ($msg) {
    echo " [x] Received '{$msg->body}'\n";
});

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();
