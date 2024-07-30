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



Task::load();
