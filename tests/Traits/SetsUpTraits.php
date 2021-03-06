<?php

namespace Tests\Traits;

trait SetsUpTraits
{
    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function setUpTraits()
    {
        $uses = parent::setUpTraits();

        if (isset($uses[\App\Traits\AttachesRepositories::class])) {
            $this->afterApplicationCreated(function () {
                $this->attachRepositories();
            });
        }

        if (isset($uses[\Tests\Traits\CreatesUsers::class])) {
            $this->afterApplicationCreated(function () {
                $this->artisan('db:seed', ['--class' => 'PermissionsSeeder']);
            });
        }

        return $uses;
    }
}
