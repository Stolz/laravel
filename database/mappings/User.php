<?php

namespace Doctrine\Mappings;

use LaravelDoctrine\Fluent\EntityMapping;

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
     * @param  \LaravelDoctrine\Fluent\Fluent $builder
     * @return void
     */
    public function map(\LaravelDoctrine\Fluent\Fluent $builder)
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
