<?php

namespace Bibo\Mvc\Core\Model;

abstract class Model
{
    protected static string $table;

    protected static string $primaryKey;

    protected static array $fillable = [];

    protected static array $hidden = [];

    protected static array $guarded = [];


    /**
     * Create a new model instance.
     */
    public function __construct()
    {
        // TODO: Implement __construct() method.
    }

    /**
     * Find a model by its primary key.
     *
     * @param  int $id The primary key value
     * @return static|null The model instance or null if not found
     */
    public static function find(int $id): ?static
    {
        // TODO: Implement find() method.
        return null;
    }

    /**
     * Get all models from the database.
     *
     * @return array<int, static> Array of model instances
     */
    public static function all(): array
    {
        // TODO: Implement all() method.
        return [];
    }

    /**
     * Create a new model with the given data.
     *
     * @param  array<string, mixed> $data The data to create the model with
     * @return static The created model instance
     */
    public static function create(array $data): static
    {
        // TODO: Implement create() method.
        return new static();
    }

    /**
     * Update the model with the given data.
     *
     * @param  array<string, mixed> $data The data to update the model with
     * @return bool True if the update was successful, false otherwise
     */
    public static function update(array $data): bool
    {
        // TODO: Implement update() method.
        return false;
    }

    /**
     * Delete a model by its primary key.
     *
     * @param  int $id The primary key value
     * @return bool True if the deletion was successful, false otherwise
     */
    public static function delete(int $id): bool
    {
        // TODO: Implement delete() method.
        return false;
    }

    /**
     * Execute a raw SQL query.
     *
     * @param  string $query The SQL query to execute
     * @param  array<int|string, mixed> $params The parameters to bind to the query
     * @return array<int, array<string, mixed>>|bool Array of results for SELECT queries, or boolean for other queries
     */
    public static function query(string $query, array $params): array|bool
    {
        // TODO: Implement query() method.
        return [];
    }

    /**
     * Convert the model to an array.
     *
     * @return array<string, mixed> The model attributes as an array
     */
    public static function toArray(): array
    {
        // TODO: Implement toArray() method.
        return [];
    }

    /**
     * Convert the model to JSON.
     *
     * @return string The model attributes as a JSON string
     */
    public static function toJson(): string
    {
        // TODO: Implement toJson() method.
        return json_encode(static::toArray()) ?: '{}';
    }

    /**
     * Clean up resources when the model is destroyed.
     */
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}
