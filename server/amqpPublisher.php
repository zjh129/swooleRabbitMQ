<?php
include(__DIR__ . '/config.php');
//发布者
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$exchange = 'router';
$queue = 'msgs';
//创建连接
$connection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
//通道
$channel = $connection->channel();
/**
 * 下面代码在消费者和生产者中是相同的，用这种方式确保在交换器中是相同
 */
//设定队列持久化
$channel->queue_declare($queue, false, true, false, false);
//设定交换机持久化
$channel->exchange_declare($exchange, 'direct', false, true, false);
//绑定队列和交换机
$channel->queue_bind($queue, $exchange);

for ($i = 1; $i <= 100; $i++){
    $messageBody = implode(' ', array_slice($argv, 1)) . $i;
    $message = new AMQPMessage($messageBody, ['content_type'=>'text/plain', 'devlivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
    $channel->basic_publish($message, $exchange);
}

$channel->close();
$connection->close();