<?php
namespace Controllers;

use App;

abstract class Controller
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function isAuthenticatedUser()
    {
        return !is_null($this->app->session()->get('user'));
    }

    public function forbidden()
    {
        header('HTTP/1.0 403 Forbidden');
        die('Forbidden! You must <a href="' . $this->app->router()::baseUrl() . 'user/form/login">log in</a>.');
    }

    public function notFound()
    {
        header('HTTP/1.0 403 Not Found');
        die('404! Not found.');
    }

    public function redirect($url)
    {
        // naive implementation
        if (!preg_match("|^https?://|", $url)) { // for relative links
            $url = $this->app->router()->baseUrl() . $url;
        }
        header('Location:' . $url, /* replace */ true, 302);
        exit;
    }

    public function redirectBack()
    {
        // naive implementation
        $back_url = $_SERVER["HTTP_REFERER"] ?? 'index';

        $this->redirect($back_url);
    }


    protected function get($key)
    {
        return $this->param(INPUT_GET, $key);
    }

    protected function post($key)
    {
        return $this->param(INPUT_POST, $key);
    }

    protected function param($type, $key)
    {
        $flags = FILTER_SANITIZE_STRING;

        if ($key == "email") {
            $flags |= FILTER_SANITIZE_EMAIL;
        }

        return filter_input($type, $key, $flags);
    }


}
