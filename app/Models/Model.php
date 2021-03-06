<?php

namespace App\Models;

use App\Traits\Identifiable;
use App\Traits\Makeable;
use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

abstract class Model implements ArrayAccess, Arrayable, Jsonable, JsonSerializable
{
    use Identifiable, Makeable;

    /**
     * Default format used for dates with time.
     *
     * @const string
     */
    const DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * Fields to hide when converting the model to JSON.
     *
     * @const array
     */
    const JSON_HIDDEN = [];

    /**
     * Fields that contain the "many" part of a OneToMany or ManyToMany relationship.
     * Constructor will automatically initialize them.
     *
     * @const array
     */
    const RELATIONSHIPS = [];

    // Meta ========================================================================

    /**
     * Model constructor.
     *
     * @param  array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        // Initialize OneToMany/ManyToMany relationships
        foreach (static::RELATIONSHIPS as $relationship) {
            $this->{$relationship} = new \Doctrine\Common\Collections\ArrayCollection();
        }

        if ($attributes) {
            $this->set($attributes);
        }
    }

    // Relationships ===============================================================

    // Getters =====================================================================

    // Setters =====================================================================

    /**
     * Set model attributes that have a setter method.
     *
     * Array keys can be in snake_case, camelCase or StudlyCase, it doesn't matter.
     *
     * @param  array $attributes
     * @return self
     */
    public function set(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            $method = 'set' . studly_case($key);

            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }

        return $this;
    }

    // Transformers ================================================================

    /**
     * Convert the model instance to string.
     *
     * @return string
     */
    abstract public function __toString(): string;

    /**
     * Convert the model to its array representation.
     *
     * Implements \Illuminate\Contracts\Support\Arrayable interface.
     *
     * NOTE: By convention array keys must be in snake_case, not in camelCase or StudlyCase.
     *
     * @return array
     */
    abstract public function toArray(): array;

    /**
     * Convert only some fields of the model to array.
     *
     * @param  array $only
     * @return array
     */
    public function toArrayOnly(array $only): array
    {
        return array_only($this->toArray(), array_map('snake_case', $only));
    }

    /**
     * Convert all but some fields of the model to array.
     *
     * @param  array $except
     * @return array
     */
    public function toArrayExcept(array $except): array
    {
        return array_except($this->toArray(), array_map('snake_case', $except));
    }

    /**
     * Convert the model to its JSON representation.
     *
     * Implements \Illuminate\Contracts\Support\Jsonable interface.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json($this->jsonSerialize(), $options);
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * Implements \JsonSerializable
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        // Separate hidden field between flat or nested
        $flat = $nested = [];
        foreach (static::JSON_HIDDEN as $key => $value) {
            if (is_array($value)) {
                $nested[$key] = $value;
            } else {
                $flat[] = $value;
            }
        }

        // Remove flat level hidden fields
        $data = $this->toArrayExcept($flat);

        // Remove nested level hidden fields
        foreach ($nested as $key => $values) {
            if (is_array($data[$key] ?? null)) {
                $data[$key] = array_except($data[$key], $values);
            }
        }

        return $data;
    }

    // Domain logic ================================================================

    /**
     * Determine if a property can be accessed via ArrayAccess.
     *
     * @param  mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        try {
            $getter = 'get' . studly_case($key);
            $method = new \ReflectionMethod($this, $getter);

            return $method->isPublic();
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Get a property via ArrayAccess.
     *
     * @param  mixed $key
     * @return mixed
     * @throws \DomainException
     */
    public function offsetGet($key)
    {
        if (! $this->offsetExists($key)) {
            throw new \DomainException(sprintf("Array access of class '%s' is not available for property '%s'", get_class($this), $key));
        }

        $getter = 'get' . studly_case($key);

        return $this->{$getter}();
    }

    /**
     * Set a property via ArrayAccess.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return void
     * @throws \DomainException
     */
    public function offsetSet($key, $value)
    {
        throw new \DomainException(sprintf("Array access of class '%s' is read-only", get_class($this)));
    }

    /**
     * Unset a property via ArrayAccess.
     *
     * @param  string  $key
     * @return void
     * @throws \DomainException
     */
    public function offsetUnset($key)
    {
        throw new \DomainException(sprintf("Array access of class '%s' is read-only", get_class($this)));
    }
}
