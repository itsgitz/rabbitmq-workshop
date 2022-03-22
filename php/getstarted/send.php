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

$channel->queue_declare('hello', false, false, false, false);

$data = [
    'nama' => 'Anggit M Ginanjar',
    'position' => 'Software Developer'
];

$jsonData = json_encode( $data, true );

$msg = new AMQPMessage($jsonData);

$channel->basic_publish($msg, '', 'hello');

echo 'Sent "Hello World!"' . PHP_EOL;

$channel->close();
$connection->close();
