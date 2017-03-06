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
        return __METHOD__.PHP_EOL;
    }
    public function time()
    {
        return __METHOD__.PHP_EOL;
    }
}
