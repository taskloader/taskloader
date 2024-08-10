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
$autoload->addNamespace('\TaskLoader', __DIR__);
$autoload->register();


/*
 *			Handle Errors
 */
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

/*
 * 			STARTUP TaskLoader
 */

// Set aliass
class_alias('TaskLoader\Task', 'Task');

if ( Task::feature('aliasses') ) {
	class_alias('\TaskLoader\TaskLoader', 'TaskLoader');
	class_alias('\TaskLoader\Core\ServiceFacade', 'Facade');
	class_alias('\TaskLoader\Core\ServiceContainer', 'Services');
	class_alias('\TaskLoader\Core\ContainerProvider', 'Container');
}



// Setup variables
if ( Task::feature('variables') ) {
	$task = Task::instance();
	/*$service = Task::get('service');
	$router = Task::get('router');
	$config = Task::get('config');*/
    extract($this->task->service->asArray()); // Are they loaded yet?
}

if ( Task::feature('facades') ) {
	class_alias('TaskLoader\Facade\Service', 'Service');
	class_alias('TaskLoader\Facade\Config', 'Config');
	class_alias('TaskLoader\Facade\Resolve', 'Resolve');
	class_alias('TaskLoader\Facade\Router', 'Router');
}

Task::load();
