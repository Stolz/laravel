<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

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

        return $uses;
    }
}
