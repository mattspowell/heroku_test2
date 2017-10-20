<?php
//namespace App\Provider;

use Gamma\ApiLoggerBundle\GammaApiLoggerBundle;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Api\EventListenerProviderInterface;
use Silex\Application;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class GammaApiLoggerServiceProvider implements ServiceProviderInterface, BootableProviderInterface, EventListenerProviderInterface
{
    public function register(Container $app)
    {
        $app['GammaApiLogger'] = new Gamma\ApiLoggerBundle\GammaApiLoggerBundle();
    }

    public function boot(Application $app)
    {
    }

    public function subscribe(Container $app, EventDispatcherInterface $dispatcher)
    {
        $dispatcher->addListener(KernelEvents::REQUEST, function (FilterResponseEvent $event) use ($app) {
            // do something
        });
    }
}
