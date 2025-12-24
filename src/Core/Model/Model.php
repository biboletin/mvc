<?php

namespace Bibo\Mvc\Core\Model;

abstract class Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected static string $table;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected static string $primaryKey;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected static array $fillable = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<string>
     */
    protected static array $hidden = [];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected static array $guarded = [];


    /**
     * Create a new model instance.
     */
    public function __construct()
    {
        // Initialize model state here if needed (e.g., set defaults or establish connections).
    }

    /**
     * Find a model by its primary key.
     *
     * @param int $id The primary key value
     *
     * @return static|null The model instance or null if not found
     */
    public static function find(int $id): ?static
    {
        // Query the underlying data source by primary key and return a populated model instance, or null if not found.
        return null;
    }

    /**
     * Get all models from the database.
     *
     * @return array<int, static> Array of model instances
     */
    public static function all(): array
    {
        // Retrieve all records from the data source and return them as an array of model instances.
        return [];
    }

    /**
     * Create a new model with the given data.
     *
     * @param array<string, mixed> $data The data to create the model with
     *
     * @return static The created model instance
     */
    public static function create(array $data): static
    {
        // Persist the provided attributes to the data source and return the newly created model instance.
        return new static();
    }

    /**
     * Update the model with the given data.
     *
     * @param array<string, mixed> $data The data to update the model with
     *
     * @return bool True if the update was successful, false otherwise
     */
    public static function update(array $data): bool
    {
        // Apply the given attributes to an existing record and return true on success; false otherwise.
        return false;
    }

    /**
     * Delete a model by its primary key.
     *
     * @param int $id The primary key value
     *
     * @return bool True if the deletion was successful, false otherwise
     */
    public static function delete(int $id): bool
    {
        // Remove the record identified by the given primary key and return true on success; false otherwise.
        return false;
    }

    /**
     * Execute a raw SQL query.
     *
     * @param string $query The SQL query to execute
     * @param array<int|string, mixed> $params The parameters to bind to the query
     *
     * @return array<int, array<string, mixed>>|bool Array of results for SELECT queries, or boolean for other queries
     */
    public static function query(string $query, array $params): array|bool
    {
        // Execute the SQL statement with bound parameters; return result set for SELECT queries or boolean for write operations.
        return [];
    }

    /**
     * Convert the model to an array.
     *
     * @return array<string, mixed> The model attributes as an array
     */
    public static function toArray(): array
    {
        // Return a primitive array representation of the model suitable for APIs, logs, or persistence.
        return [];
    }

    /**
     * Convert the model to JSON.
     *
     * @return string The model attributes as a JSON string
     */
    public static function toJson(): string
    {
        // Convert the model's array representation to JSON; customize encoding options as needed.
        return json_encode(static::toArray()) ?: '{}';
    }

    /**
     * Clean up resources when the model is destroyed.
     */
    public function __destruct()
    {
        // Perform any necessary cleanup for the model (e.g., freeing resources or closing connections).
    }
}
