<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseTestCase;
use Tests\Traits\CreatesApplication;
use Tests\Traits\SetsUpTraits;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication, SetsUpTraits;

    /**
     * Prepare for Dusk test execution.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',   // GPU enabled renders black screenshots
            //'--headless',    // headless mode does not work in my system
            //'--no-sandbox',
            '--window-size=1920,1200',
        ]);

        $capabilities = DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $options);

        return RemoteWebDriver::create('http://localhost:9515', $capabilities);
    }
}
