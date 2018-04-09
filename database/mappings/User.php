<?php

namespace Doctrine\Mappings;

use LaravelDoctrine\Fluent\EntityMapping;
use LaravelDoctrine\Fluent\Fluent;

class User extends EntityMapping
{
    /**
     * Returns the fully qualified name of the class that this mapper maps.
     *
     * @return string
     */
    public function mapFor()
    {
        return \App\Models\User::class;
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
        $builder->string('name');
        $builder->string('email')->unique();
        $builder->string('password');
        $builder->string('rememberToken')->length(100)->nullable();
        $builder->carbonDateTime('createdAt')->nullable();
        $builder->carbonDateTime('updatedAt')->nullable();
        $builder->carbonDateTime('deletedAt')->nullable();

        // Relationships
        $builder->manyToOne(\App\Models\Role::class); // unidirectional. owning side
    }
}
