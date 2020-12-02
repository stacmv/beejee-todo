<?php

namespace Models;

class Task extends FileModel
{
    const ITEMS_PER_PAGE = 3;
    const COMPLETED = 1; // completed state value;
    const UNCOMPLETED = 02; // uncompleted state value;
    const EDITED = 1; // task edited by admin
    const NOT_EDITED = 0;

    protected static $collection = 'tasks';
    protected static $fields = ['id', 'name', 'email', 'text', 'state', 'edited'];

    public function __construct(array $data)
    {
        if (!isset($data['state'])) {
            $data['state'] = self::UNCOMPLETED; // uncompleted by default
        }
        if (!isset($data['edited'])) {
            $data['edited'] = self::NOT_EDITED;
        }

        parent::__construct($data);
    }

    public static function byId($task_id)
    {
        return parent::byKey('id', $task_id);
    }
}

