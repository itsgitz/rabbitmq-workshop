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

    sleep(substr_count($msg->body, '.'));

    echo $msg->body . PHP_EOL;
    echo 'Done' . PHP_EOL;

    $msg->ack;
};

$channel->basic_consume('proxmox_node_repair_data_collection', '', false, false, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();
