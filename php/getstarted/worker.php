<?php

require './vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$queueName = 'proxmox_nodes_repair';

$connection = new AMQPStreamConnection(
    'localhost',
    5672,
    'guest',
    'guest'
);

$channel = $connection->channel();

$channel->queue_declare(
    $queueName,
    false,
    true, // durable
    false,
    false
);

echo 'Waiting for message ...' . PHP_EOL;

$callback = function ($msg) {
    echo 'Received: ' . PHP_EOL;

    sleep(substr_count($msg->body, '.'));

    echo $msg->body . PHP_EOL;
    echo 'Done' . PHP_EOL;

    $msg->ack();
};

$channel->basic_qos(null, 1, null);

$channel->basic_consume(
    $queueName,
    '',
    false,
    false, // true means no ACK
    false,
    false,
    $callback
);

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();
