<?php

namespace Bibo\Core\Model;

abstract class Model
{
    protected static string $table;

    protected static string $primaryKey;

    protected static array $fillable = [];

    protected static array $hidden = [];

    protected static array $guarded = [];


    public function __construct()
    {
        // TODO: Implement __construct() method.
    }

    public static function find(int $id)
    {
        // TODO: Implement find() method.
    }

    public static function all()
    {
        // TODO: Implement all() method.
    }

    public static function create(array $data)
    {
        // TODO: Implement create() method.
    }

    public static function update(array $data)
    {
        // TODO: Implement update() method.
    }

    public static function delete(int $id)
    {
        // TODO: Implement delete() method.
    }

    public static function query(string $query, array $params)
    {
        // TODO: Implement query() method.
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}
