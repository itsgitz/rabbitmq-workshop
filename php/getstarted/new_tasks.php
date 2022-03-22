<?php

require './vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

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

$data = implode(' ', array_slice($argv, 1));

if (empty($data)) {
    $data = 'Hello World';
}

$msg = new AMQPMessage(
    $data,
    ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
);

$channel->basic_publish($msg, '', $queueName);

echo ' Sent ' . $data . PHP_EOL;

$channel->close();
$connection->close();
