<?php

namespace App;

use Silex\Application;

class ServicesLoader
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function bindServicesIntoContainer()
    {
        $this->app['stores.service'] = function() {
            return new Services\StoresService();
        };
        $this->app['appointments.service'] = function() {
            return new Services\AppointmentsService();
        };
    }
}

