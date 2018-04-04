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
        $builder->table('users');
        $builder->increments('id');
        $builder->string('name');
        $builder->string('email')->unique();
        $builder->string('password');
        $builder->string('rememberToken')->length(100)->nullable();
        $builder->datetime('createdAt')->nullable();
        $builder->datetime('updatedAt')->nullable();
        $builder->datetime('deletedAt')->nullable();
    }
}
