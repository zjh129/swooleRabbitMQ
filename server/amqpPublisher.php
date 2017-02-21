<?php
//使用composer扩展
require_once '../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('my-rabbit', 5672, 'guest','guest','/');

$queue = new AMQPQueue($channel);
$queue->setName($queueName);
$queue->declare();