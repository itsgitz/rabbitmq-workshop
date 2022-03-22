<?php

require './vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection(
    'localhost',
    5672,
    'guest',
    'guest'
);

$channel = $connection->channel();

$data = implode(' ', array_slice($argv, 1));

if (empty($data)) {
    $data = 'Hello World';
}

$msg = new AMQPMessage($data);

$channel->basic_publish($msg, '', 'hello');

echo ' Sent ' . $data . PHP_EOL;

$channel->close();
$connection->close();
