<?php

class _Session
{
    protected static $session_started;

    public function __construct()
    {
        if (!self::$session_started) {
            session_start();
            self::$session_started = true;
        }
    }

    public function forget($key)
    {
        unset($_SESSION[$key]);
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function put($key, $value)
    {
        $_SESSION[$key] = $value;
    }
}
