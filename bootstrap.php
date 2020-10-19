<?php declare(strict_types = 1);


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);




/*
 * Autoload
 */
include 'autoloader.php';
$autoload = new Autoloader();
$autoload->addNamespace('Whoops', __DIR__.'/../whoops/src/Whoops');
$autoload->addNamespace('\TaskFiber', __DIR__);
$autoload->register();


/*
 *			Handle Errors
 */
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

/*
 * 			STARTUP TASKFIBER
 */

// Set aliass
class_alias('TaskFiber\Fiber', 'Fiber');


// compile from file later

// Setup features
Fiber::enable('variables');
Fiber::enable('aliasses');
Fiber::disable('facades');
// FIber::disable('debug');



if ( Fiber::feature('aliasses') ) {
	class_alias('TaskFiber\TaskFiber', 'TaskFiber');
	class_alias('TaskFiber\Core\ServiceFacade', 'Facade');
	class_alias('\TaskFiber\Core\ServiceContainer', 'Services');
	class_alias('\TaskFiber\Core\ContainerProvider', 'Container');
}



// Setup variables
if ( Fiber::feature('variables') ) {
	$fiber = Fiber::instance();
	$service = Fiber::get('service');
	$router = Fiber::get('router');
	$config = Fiber::get('config');
}

if ( Fiber::feature('facades') ) {
	class_alias('TaskFiber\Facade\Service', 'Service');
	class_alias('TaskFiber\Facade\Config', 'Config');
	class_alias('TaskFiber\Facade\Resolve', 'Resolve');
	class_alias('TaskFiber\Facade\Router', 'Router');
}

Fiber::load();

echo \TaskFiber\Environment::databaseHostname();