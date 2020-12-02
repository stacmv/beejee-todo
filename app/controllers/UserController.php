<?php

namespace Controllers;

use View;
use Exception;
use Models\User;
use Controllers\Controller;

class UserController extends Controller
{
    public function formLogin()
    {
        $page_title = "Log In";

        return (new View($this->app))->make('user/login_form', compact('page_title'));
    }

    public function login()
    {
        $login = $this->post('login');
        $pass = $this->post('pass');

        try {
            $user = User::byLogin($login);

            if (password_verify($pass, $user->pass)) {
                $this->app->session()->put('user', $user);
                $this->app->session()->forget('msg');
                $this->redirect('index');
            }

            throw new Exception('Wrong password for "' . $login . '".'); // @TODO log this to catch hackers attacks and dumb forgotten their passwords.
        } catch (Exception $e) {
            // Cases: user not found or wrong password.
            // Do not show exception message to user, it is for logging purpose.
            $this->app->session()->put('msg', 'Incorrect login or password.');
            $this->app->session()->forget('user');
            $this->redirect('user/form/login');
        }
    }

    public function logout()
    {
        $this->app->session()->put('msg', 'Logged out.');
        $this->app->session()->forget('user');
        $this->redirect('index');
    }
}
