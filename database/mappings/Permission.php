<?php

namespace Doctrine\Mappings;

use LaravelDoctrine\Fluent\EntityMapping;
use LaravelDoctrine\Fluent\Fluent;

class Permission extends EntityMapping
{
    /**
     * Returns the fully qualified name of the class that this mapper maps.
     *
     * @return string
     */
    public function mapFor()
    {
        return \App\Models\Permission::class;
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
    }
}
