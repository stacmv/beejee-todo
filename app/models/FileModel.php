<?php

namespace Models;

use Exception;

abstract class FileModel extends Model
{
    const DATA_PATH = ".data/";
    const DELIMITER = "\t";

    protected static $cached = [];

    public function save()
    {
        $tsv_strings = self::file();

        if (!$this->id) {
            $this->id = self::lastKnownId() + 1 ;
        }
        $tsv_strings[$this->id] = $this->toTsvString();

        file_put_contents(self::file_name(), implode("\n", $tsv_strings));

        self::$cached[self::file_name()] = null;

    }

    protected function toTsvString()
    {
        $saved_values = [];
        foreach(static::$fields as $key) {
            $saved_values[] = $this->$key ?? ''; // @TODO: if value has DELIMETER in it, we have a problem;
        }

        $tsv_string = implode(self::DELIMITER, $saved_values);

        return $tsv_string;
    }

    protected static function byKey($key, $value)
    {
        // Just for simplicity and for the sake of DRY

        $items = static::paginate(1, "", static::count()); // get all data. This is a test app, very few data will be stored.

        // Ignoring sort & binaru search ... 2am now.
        foreach($items as $item) {
            if ($item->$key == $value) {
                return $item;
            };
        }

        throw new Exception(static::class . ' with ' . $key . ' = "' . $value . '" not found.');
    }

    public static function count()
    {
        return count(self::file()) - 1; // lines in file minus header
    }

    public static function paginate(int $page, $sort = '', $items_per_page = null): array
    {

        $page = $page >= 1 ? $page : 1;

        $limit = $items_per_page ?? static::ITEMS_PER_PAGE;
        $offset = ($page - 1) * $limit;

        $tsv = self::file();
        array_shift($tsv); // strip header

        // Get all .. noSql :)
        $objects = array_map(function($tsv_string) {
            return self::fromTsv($tsv_string);
        }, $tsv);

        // Sort
        if ($sort) {
            $field = ltrim($sort, '-');
            $order = substr($sort, 0, 1) == '-' ? 'DESC' : 'ASC';

            if (in_array($field, static::$fields)) { // check if field requested for sort exists.
                uasort($objects, function($a, $b) use ($field, $order) {
                    if ($a->$field == $b->$field) {
                        return 0;
                    }

                    if ($order == 'DESC') {
                        // DESC order
                        return $a->$field < $b->$field;
                    } else {
                        // ASC order
                        return $a->$field > $b->$field;
                    }
                });
            }
        }

        // Paginate
        $objects = array_slice($objects, $offset, $limit);


        return $objects;
    }

    protected static function fromTsv(string $tsv_string): Model
    {
        // Header, fields names
        $fields = explode(self::DELIMITER, trim(static::file()[0])); // 0th element always exists.

        // Record, fields values
        $values = explode(self::DELIMITER, trim($tsv_string));

        if (count($values) > count($fields)) {
            $values = array_slice($values, 0, count($fields));
        } elseif (count($values) < count($fields)) {
            throw new Exception(static::class . ' file  seam to be corrupted.');
        }

        $data = array_combine($fields, $values);

        return new static($data);

    }

    protected static function file_name()
    {
        if (!is_dir(self::DATA_PATH)) {
            mkdir(self::DATA_PATH, 0777, true);
        }
        $file_name = self::DATA_PATH . static::$collection . ".tsv";
        if (!file_exists($file_name)) {
            touch($file_name);
        }

        return $file_name;
    }
    protected static function file(): array
    {
        $file_name = self::file_name();

        if (empty(self::$cached[$file_name])) {

            self::$cached[$file_name] = array_filter(array_map('trim', file($file_name))); // Avoid empty lines
        }

        $tsv = self::$cached[$file_name];

        if (empty($tsv)) {
            $tsv = [implode(self::DELIMITER, static::$fields)]; // for empty files returtn just header generated on the fly
        }

        return $tsv;
    }

    protected static function lastKnownId()
    {
        // @TODO: this methos is suboptimal: should remember last id when saving.
        $tsv_strings = self::file();

        $last_known_id = 0;
        if (count($tsv_strings) <= 1) { // Only header abd no actual data
            return $last_known_id;
        }

        $last_known_id= array_reduce($tsv_strings, function($last_known_id, $record) {
            $id = (int) static::fromTsv($record)->id;
            $last_known_id = max([$last_known_id, $id]);
            return $last_known_id;
        }, $last_known_id);

        return $last_known_id;
    }
}

