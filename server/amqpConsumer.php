<?php
include(__DIR__.'/config.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
//交换器名称
$exchange = 'router';
//队列名称
$queue = 'msgs';
//消费这标签
$consumerTag = 'consumer';
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

/**
 * @param \PhpAmqpLib\Message\AMQPMessage $message
 */
function process_message($message)
{
    echo "\n--------\n";
    echo $message->body;
    echo "\n--------\n";

    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

    if ($message->body === 'quit') {
        $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
    }
}

$channel->basic_consume($queue, $consumerTag, false, false, false, false, 'process_message');

/**
 * @param \PhpAmqpLib\Channel\AMQPChannel $channel
 * @param \PhpAmqpLib\Connection\AbstractConnection $connection
 */
function shutdown($channel, $connection)
{
    $channel->close();
    $connection->close();
}

register_shutdown_function('shutdown', $channel, $connection);

while (count($channel->callbacks)) {
    $channel->wait();
}
