<?php

require './vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection(
    'localhost',
    5672,
    'guest',
    'guest'
);

$channel = $connection->channel();

$channel->queue_declare('hello', false, false, false, false);

echo 'Waiting for message ...' . PHP_EOL;

$callback = function ($msg) {
    echo 'Received: ' . PHP_EOL;
    echo $msg->body . PHP_EOL;
};

$channel->basic_consume('hello', '', false, true, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();
