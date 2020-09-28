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
Fiber::alias('Service\ServiceProvider', 'Service');
Fiber::alias('Service\ServiceFacade', 'Facade');

//Fiber::init();
Fiber::bind('config', 'Config');
Fiber::bind('router', 'Router');
Fiber::bind('session', 'Session');
Fiber::bind('auth', 'Authenticate');
Fiber::attach('query', 'Query');

class TemporaryConfig extends Service {
	public $count = 0;

	public function instance()
	{
		return $this;
	}


	public function plus()
	{
		$this->count++;
	}

	public function invoke()
	{
		echo "I am a router";
	}
}
Fiber::resolve('Config', 'TemporaryConfig');
Fiber::resolve('Query', 'TemporaryConfig');

Fiber::service('config')->plus();

class Config extends Facade {}


$config = Config::instance();
$config->plus();

echo $config->count;


Fiber::service('query')->plus();
$query = Fiber::service('query');
$query->plus();
echo $query->count;