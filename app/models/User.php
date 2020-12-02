<?php
namespace Models;

use Exception;

class User extends FileModel
{
    const ITEMS_PER_PAGE = 999; // KISS: just to get all users. Supposed only one.

    protected static $collection = 'users';
    protected static $fields = ['id', 'login', 'pass'];

    public static function byLogin(string $login): User
    {
        return parent::byKey('login', $login);
    }

}
