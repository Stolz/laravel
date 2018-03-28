<?php

namespace App\Models;

use App\Traits\Identifiable;
use App\Traits\Makeable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

abstract class Model implements Arrayable, Jsonable, JsonSerializable
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

    // Meta ========================================================================

    /**
     * Model constructor.
     *
     * @param  array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        if ($attributes) {
            $this->set($attributes);
        }
    }

    // Gettets =====================================================================

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
        return array_only($this->toArray(), $only);
    }

    /**
     * Convert all but some fields of the model to array.
     *
     * @param  array $except
     * @return array
     */
    public function toArrayExcept(array $except): array
    {
        return array_except($this->toArray(), $except);
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
        return $this->toArrayExcept(static::JSON_HIDDEN);
    }

    // Domain logic ================================================================
}
