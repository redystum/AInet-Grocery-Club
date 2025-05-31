<?php

namespace App\Utils;

/**
 * Manages custom fields for an Eloquent model instance.
 */
class CustomFieldManager
{

    protected mixed $eloquent_instance;

    /**
     * Constructor to initialize the custom manager for the Eloquent model.
     *
     * @param mixed $instance An Eloquent model instance
     * @return void
     */
    public function __construct(mixed $instance)
    {
        $this->eloquent_instance = $instance;
        $instance->setAttribute('customManager', $this);
    }

    /**
     * Retrieve a value associated with the specified key from the custom property of the eloquent instance.
     *
     * @param string $key The key used to access the value in the custom property.
     * @param mixed $default The default value to return if the key does not exist or the instance does not exist.
     *
     * @return mixed The value associated with the key or the default value if not found.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        if (!$this->exists()) {
            return $default;
        }

        $this->not_array_convert_it();
        return $this->eloquent_instance->custom[$key] ?? $default;
    }

    /**
     * Checks if a custom field exists in the eloquent model instance
     *
     * @return bool True if the custom field exists, otherwise false
     */
    public function exists(): bool
    {
        return isset($this->eloquent_instance->custom);
    }

    /**
     * Assign a value to the specified key within the custom property of the eloquent instance.
     *
     * @param string $key The key where the value should be stored in the custom property.
     * @param mixed $value The value to be assigned to the specified key.
     *
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->not_array_convert_it();
        $custom = $this->eloquent_instance->custom ?? [];
        $custom[$key] = $value;
        $this->eloquent_instance->custom = $custom;
    }

    /**
     * Assigns all key-value pairs from the custom property of the eloquent instance as attributes of the instance.
     *
     * Iterates through each item in the custom property and sets it as an attribute
     * on the eloquent instance, provided the instance exists.
     *
     * @return void
     */
    public function to_attribute(): void
    {
        if ($this->exists()) {
            $this->not_array_convert_it();
            foreach ($this->eloquent_instance->custom as $key => $value) {
                $this->eloquent_instance->setAttribute($key, $value);
            }
        }
    }

    private function not_array_convert_it(): void
    {
        if (!$this->exists()) {
            return;
        }

        if (!is_array($this->eloquent_instance->custom)) {
            $this->eloquent_instance->custom = json_decode($this->eloquent_instance->custom, true);
        }
    }

    // Region Static methods


    /**
     * Sets custom fields as attributes on the model
     *
     * @param mixed $instance An eloquent model instance
     * @param array<string,mixed> $custom Array of custom fields
     * @return void
     */
    public static function custom_to_attribute(mixed $instance, array $custom): void
    {
        foreach ($custom as $key => $value) {
            $instance->setAttribute($key, $value);
        }
    }

    /**
     * Maps custom attributes to model attributes
     *
     * @param mixed $instance An eloquent model instance
     * @return void
     */
    public static function self_custom_to_attribute(mixed $instance): void
    {
        if (isset($instance->custom)) {
            if (!is_array($instance->custom)) {
                $instance->custom = json_decode($instance->custom, true);
            }
            foreach ($instance->custom as $key => $value) {
                $instance->setAttribute($key, $value);
            }
        }
    }

    /**
     * Adds or updates a custom field on the model
     *
     * @param mixed $instance An eloquent model instance
     * @param string $key The custom field key
     * @param mixed $value The custom field value
     * @return void
     */
    public static function add_or_update_custom_field(mixed $instance, string $key, mixed $value): void
    {
        $custom = $instance->custom ?? [];
        $custom[$key] = $value;
        $instance->custom = $custom;
    }

    /**
     * Gets a custom field value from the model
     *
     * @param mixed $instance An eloquent model instance
     * @param string $key The custom field key
     * @return mixed The custom field value or null if not found
     */
    public static function get_field(mixed $instance, string $key): mixed
    {
        if (!isset($instance->custom)) {
            return null;
        }

        if (!is_array($instance->custom)) {
            $instance->custom = json_decode($instance->custom, true);
        }

        return $instance->custom[$key] ?? null;
    }

    /**
     * Updates the custom array with values from the provided data array.
     *
     * @param mixed $custom The array to be updated. If not an array, the data array is returned.
     * @param array $data The data array containing key-value pairs to update the custom array.
     * @param bool $stringify If true, the resulting array will be JSON-encoded.
     * @return array|string The resulting array after merging values from the data array into the custom array.
     */
    public static function update_or_create_array(mixed $custom, array $data, bool $stringify = false): array|string
    {
        if (!$custom) {
            if ($stringify) {
                $data = json_encode($data);
            }
            return $data;
        }

        if (!is_array($custom)) {
            $custom = json_decode($custom, true);
            if (!$custom || !is_array($custom)) {
                if ($stringify) {
                    $data = json_encode($data);
                }
                return $data;
            }
        }

        foreach ($data as $key => $value) {
            $custom[$key] = $value;
        }

        if ($stringify) {
            $custom = json_encode($custom);
        }
        return $custom;
    }


    // End Region
}