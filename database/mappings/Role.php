<?php

namespace Doctrine\Mappings;

use LaravelDoctrine\Fluent\EntityMapping;
use LaravelDoctrine\Fluent\Fluent;

class Role extends EntityMapping
{
    /**
     * Returns the fully qualified name of the class that this mapper maps.
     *
     * @return string
     */
    public function mapFor()
    {
        return \App\Models\Role::class;
    }

    /**
     * Load the object's metadata through the Metadata Builder object.
     *
     * @param Fluent $builder
     * @return void
     */
    public function map(Fluent $builder)
    {
        $builder->increments('id');
        $builder->string('name')->unique();
        $builder->string('description')->nullable();
        $builder->carbonDateTime('createdAt')->nullable();
        $builder->carbonDateTime('updatedAt')->nullable();
    }
}
