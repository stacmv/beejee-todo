<?php

class Config
{
    private $routes = [
        'get' => [
            '/' => ['Task', 'list'],
            'index' => ['Task', 'list'],
            'task/list' => ['Task', 'list'],
            'task/form/add' => ['Task', 'formAdd'],
            'task/form/edit' => ['Task', 'formEdit'],
            'user/form/login' => ['User', 'formLogin'],
            'user/logout' => ['User', 'logout'], // logout allowed both by GET and POST
        ],
        'post' => [
            'task/add' => ['Task', 'add'],
            'task/edit' => ['Task', 'edit'],
            'task/complete' => ['Task', 'complete'],
            'task/uncomplete' => ['Task', 'uncomplete'],
            'user/login' => ['User', 'login'],
            'user/logout' => ['User', 'logout'],
        ],
    ];

    private $session = '_SESSION';


    public function __construct()
    {

    }

    public function router()
    {
        return new Router($this->routes);
    }

    public function session()
    {
        if ($this->session == "_SESSION") {
            return new _Session();
        } else {
            throw new Exception('Wrong session handle class. See Config.');
        }
    }
}
