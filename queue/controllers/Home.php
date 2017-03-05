<?php
namespace App\Controller;

use Swoole;

class Home extends Swoole\Controller
{
    public function __construct($swoole)
    {
        parent::__construct($swoole);
        /*Swoole::$php->session->start();
        Swoole\Auth::loginRequire();*/
    }

    public function index()
    {
        return time()."\n";
    }
    public function time()
    {
        var_dump($this->request);
        return 0;
        return date("Y-m-d H:i:s")."\n";
    }
}
