<?php

namespace Models;

abstract class Model
{
    protected $data;

    public function __construct(array $data)
    {
        foreach(static::$fields as $k) {
            $this->data[$k] = $data[$k] ?? null;
        }
    }
    public function __get($key) {
        if (!in_array($key, static::$fields)) {
            throw new Exception('Field "' . $key . '" not exists in model "' . static::class . '".');
        }

        return $this->data[$key];
    }

    public function modify(array $data): Model {

        if (isset($data['id'])) {
            unset($data['id']); // id changing not allowed
        }

        $modified_data = array_merge($this->data, $data);

        return new static($modified_data);

    }

    abstract public static function paginate(int $page, $sort = '', $items_per_page = null);
}
