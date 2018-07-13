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
        $builder->string('name')->unique();
    }
}
