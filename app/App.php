<?php

class App
{
    private $config = [];
    private $router;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->router = $config->router();
    }

    public function router()
    {
        return $this->router;
    }

    public function run()
    {

        list($controller_instance, $action) = $this->router->resolve($this);

        $html = $controller_instance->$action();
        echo $html;

    }

    public function session()
    {
        return $this->config->session();
    }



}
