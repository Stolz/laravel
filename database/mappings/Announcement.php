<?php

namespace Doctrine\Mappings;

use LaravelDoctrine\Fluent\EntityMapping;

class Announcement extends EntityMapping
{
    /**
     * Returns the fully qualified name of the class that this mapper maps.
     *
     * @return string
     */
    public function mapFor()
    {
        return \App\Models\Announcement::class;
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
        $builder->string('name')->unique();
        $builder->text('description')->nullable();
        $builder->boolean('active')->default(false);
        $builder->carbonDateTime('createdAt')->nullable();
        $builder->carbonDateTime('updatedAt')->nullable();
    }
}
