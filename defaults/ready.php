<?php

if ( Task::feature('aliasses') ) {
	class_alias('\TaskLoader\TaskLoader', 'TaskLoader');
	class_alias('\TaskLoader\Core\ServiceFacade', 'Facade');
	class_alias('\TaskLoader\Core\ServiceContainer', 'Services');
	class_alias('\TaskLoader\Core\ContainerProvider', 'Container');
}



// Setup variables
if ( Task::feature('variables') ) {
	$task = Task::instance();
	$service = Task::get('service');
	$router = Task::get('router');
	$config = Task::get('config');
}

if ( Task::feature('facades') ) {
	class_alias('TaskLoader\Facade\Service', 'Service');
	class_alias('TaskLoader\Facade\Config', 'Config');
	class_alias('TaskLoader\Facade\Resolve', 'Resolve');
	class_alias('TaskLoader\Facade\Router', 'Router');
}