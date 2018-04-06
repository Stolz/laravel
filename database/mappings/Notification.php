<?php

namespace Doctrine\Mappings;

use LaravelDoctrine\Fluent\EntityMapping;
use LaravelDoctrine\Fluent\Fluent;

class Notification extends EntityMapping
{
    /**
     * Returns the fully qualified name of the class that this mapper maps.
     *
     * @return string
     */
    public function mapFor()
    {
        return \App\Models\Notification::class;
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
        $builder->string('level');
        $builder->string('message');
        $builder->string('actionText')->nullable();
        $builder->string('actionUrl')->nullable();
        $builder->carbonDateTime('createdAt')->nullable();
        $builder->carbonDateTime('readAt')->nullable();

        // Relationships
        $builder->manyToOne(\App\Models\User::class);
    }
}
