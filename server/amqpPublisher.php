<?php
include(__DIR__ . '/config.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$exchange = 'router';
$queue = 'msgs';

$connection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
$channel = $connection->channel();

$channel->queue_declare($queue, false, true, false, false);
$channel->exchange_declare($exchange, 'direct', false, true, false);
$channel->queue_bind($queue, $exchange);

for ($i = 1; $i <= 100; $i++){
    $messageBody = implode(' ', array_slice($argv, 1)) . $i;
    $message = new AMQPMessage($messageBody, ['content_type'=>'text/plain', 'devlivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
    $channel->basic_publish($message, $exchange);
}

$channel->close();
$connection->close();