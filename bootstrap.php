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
class_alias('TaskFiber\TaskFiber', 'TaskFiber');

// compile from file later
class_alias('TaskFiber\Core\ServiceFacade', 'Facade');
class_alias('\TaskFiber\Core\ServiceContainer', 'Services');
// compile from file later
class_alias('TaskFiber\Facade\Service', 'Service');
class_alias('TaskFiber\Facade\Config', 'Config');
class_alias('TaskFiber\Facade\Resolve', 'Resolve');

//Fiber::init();
Fiber::addService('config', ConfigProvider::class);
Fiber::addService('router', 'RouterProvider');
Fiber::addService('session', 'SessionProvider');
Fiber::addService('authenticate', 'Authenticate');
Fiber::addService('query', 'QueryFactory');
