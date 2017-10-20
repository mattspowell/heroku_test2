<?php

//use App\Provider\GammaApiLoggerServiceProvider;
use App\RoutesLoader;
use App\ServicesLoader;
use Carbon\Carbon;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SwiftMailerHandler;
use Monolog\Logger;

date_default_timezone_set('Europe/London');

// Accepting JSON
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

// Register providers
$app->register(new \Euskadi31\Silex\Provider\CorsServiceProvider);
$app->register(new ServiceControllerServiceProvider());
$app->register(new HttpCacheServiceProvider(), array("http_cache.cache_dir" => ROOT_PATH . "/storage/cache"));
$app->register(new MonologServiceProvider(), array(
    "monolog.logfile" => ROOT_PATH . "/storage/logs/" . Carbon::now('Europe/London')->format("Y-m-d") . ".log",
    "monolog.level" => $app["log.level"],
    "monolog.name" => "application",
));
$app->register(new SwiftmailerServiceProvider());
// $app['swiftmailer.options'] = array(
//     'encryption' => 'ssl',
//     'auth_mode' => 'login',
// );

$app['swiftmailer.options'] = array(
    'host' => 'smtp.gmail.com',
    'port' => '465',
    'username' => 'sssasbotwebservices@gmail.com',
    'password' => '12Testin',
    'encryption' => 'ssl',
    'auth_mode' => 'login'
);
$app['swiftmailer.use_spool'] = false;

//$app->register(new GammaApiLoggerServiceProvider());

// Load services
$servicesLoader = new App\ServicesLoader($app);
$servicesLoader->bindServicesIntoContainer();

// Load routes
$routesLoader = new App\RoutesLoader($app);
$routesLoader->bindRoutesToControllers();

// Adjusting Monolog to log GET and POST requests to application

$requestBody = file_get_contents('php://input');
$data = (array)json_decode($requestBody);
$monologHandler = $app['monolog']->popHandler();
$monologFormatter = new JsonFormatter();

$monologHandler->setFormatter($monologFormatter);
$app['monolog']->pushHandler($monologHandler);
$app['monolog']->addInfo('Input Stream "{result}".', $data);

if (ROOT_PATH == ('/app/web/..')) {
    // looks like we on heroku side#
    $log = new Logger('rest_api_log');
    $handler = new StreamHandler('php://stderr', Logger::INFO);
    $handler->setFormatter($monologFormatter);
    $log->pushHandler($handler);
    $log->addInfo('Input Stream "{result}".', $data);

    $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
        ->setUsername('sssasbotwebservices@gmail.com')
        ->setPassword('12Testin');
    $mailer = \Swift_Mailer::newInstance($transport);
    $logger = new \Swift_Plugins_Loggers_ArrayLogger();
    $mailer->registerPlugin(new \Swift_Plugins_LoggerPlugin($logger));
    $message = \Swift_Message::newInstance()
        ->setSubject("JSON Log")
        ->setFrom(array("sssasbotwebservices@gmail.com"))
        ->setTo(array('sssasbotwebservices@gmail.com'))
        ->setBody($requestBody);
    $app['swiftmailer.use_spool'] = false;
    $mailer->send($message, $failures);

}

// Set up logging
$app->error(function (\Exception $e, $code) use ($app) {
    $app['monolog']->addError($e->getMessage());
    $app['monolog']->addError($e->getTraceAsString());
    return new JsonResponse(array("statusCode" => $code, "message" => $e->getMessage(), "stacktrace" => $e->getTraceAsString()));
});

return $app;
