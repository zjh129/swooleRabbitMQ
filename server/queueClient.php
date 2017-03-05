<?php
class QueueClient
{
    private $client;
    public function __construct()
    {
        $this->client = new swoole_client(SWOOLE_SOCK_TCP);

    }
    public function connect()
    {
        try{
            $this->client->connect('127.0.0.1', 9443, 1);
        }catch (Exception $e){
            echo $e->getCode().':'.$e->getMessage();
            exit();
        }
    }
    public function send($data)
    {
        $data = [
            'queueName' => 'Home/time',
            'recData' => [
                'name' => '赵建辉',
                'age' => 28,
                'data' => $data
            ],
        ];
        $jsonData = json_encode($data);
        $this->client->send($jsonData);
        //echo $this->client->recv();
    }
    public function close()
    {
        $this->client->close();
    }
}
$client = new QueueClient();
$client->connect();
for ($i=0;$i<1000;$i++){
    $client->send($i);
}
$client->close();
