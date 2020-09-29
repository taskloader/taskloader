<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include 'autoloader.php';

$autoload = new Autoloader();
$autoload->addNamespace('Whoops', __DIR__.'/../whoops/src/Whoops');
$autoload->addNamespace('\TaskFiber', __DIR__);
$autoload->register();

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

class_alias('TaskFiber\Fiber', 'Fiber');
Fiber::alias('Core\ServiceProvider', 'Service');
Fiber::alias('Core\ServiceFacade', 'Facade');

//Fiber::init();
Fiber::bind('config', 'Config');
Fiber::bind('router', 'Router');
Fiber::bind('session', 'Session');
Fiber::bind('auth', 'Authenticate');
Fiber::attach('query', 'Query');


Fiber::resolve('Config', 'TemporaryConfig');
Fiber::resolve('Query', 'ServiceClass');
Fiber::resolve('Injectable', 'Override');
