<?php

namespace App;

use Silex\Application;

class RoutesLoader
{

    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->instantiateControllers();
    }

    private function instantiateControllers()
    {
        $this->app['webhooks.controller'] = function() {
            return new Controllers\WebhooksController($this->app['stores.service'], $this->app['appointments.service'], $this->app);
        };
    }

    public function bindRoutesToControllers()
    {
        $api = $this->app["controllers_factory"];
        $api->post('/webhook', "webhooks.controller:index");
        $this->app->mount($this->app["api.endpoint"].'/'.$this->app["api.version"], $api);
    }
}

