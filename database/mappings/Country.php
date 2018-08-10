<?php

namespace Doctrine\Mappings;

use LaravelDoctrine\Fluent\EntityMapping;

class Country extends EntityMapping
{
    /**
     * Returns the fully qualified name of the class that this mapper maps.
     *
     * @return string
     */
    public function mapFor()
    {
        return \App\Models\Country::class;
    }

    /**
     * Load the object's metadata through the Metadata Builder object.
     *
     * @param  \LaravelDoctrine\Fluent\Fluent $builder
     * @return void
     */
    public function map(\LaravelDoctrine\Fluent\Fluent $builder)
    {
        $builder->smallIncrements('id');
        $builder->string('code')->length(2)->unique();
        $builder->string('name')->unique();
        $builder->carbonDateTime('createdAt')->nullable();
        $builder->carbonDateTime('updatedAt')->nullable();
        $builder->carbonDateTime('deletedAt')->nullable();
    }
}
