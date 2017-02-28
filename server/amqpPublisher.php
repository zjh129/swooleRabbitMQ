<?php
include(__DIR__ . '/config.php');
//发布者
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

//交换器名称
$exchange = 'router';
//队列名称
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

for ($i = 1; $i <= 10000; $i++){
    //截取脚本参数部分合并组成消息内容
    $messageBody = implode(' ', array_slice($argv, 1)) . microtime(true);
    //创建一个消息实体
    $message = new AMQPMessage($messageBody, ['content_type'=>'text/plain', 'devlivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
    //发送消息到队列
    $channel->basic_publish($message, $exchange);
}
//关闭通道
$channel->close();
//关闭连接
$connection->close();