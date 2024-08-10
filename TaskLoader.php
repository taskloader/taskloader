<?php namespace TaskLoader;
use TaskLoader\Core\ContainerInterface;
use TaskLoader\Core\ServiceContainer;
use TaskLoader\Core\ResolveContainer;

class TaskLoader implements ContainerInterface {
	private ServiceContainer $service;
	private BootstrapProvider $bootstrap;
	private array $features = [];
	private string $baseDir;
	private string $appDir;
	private bool $ready = false;

	use Feature\loadConfig;
	use Feature\requireFile;



	/**
	 * Constructs a new instance.
	 */
	public function __construct( string $path )
	{
		$this->baseDir = realpath($path).\DIRECTORY_SEPARATOR;
		$this->appDir = realpath($this->baseDir.'../').\DIRECTORY_SEPARATOR;

		if ( is_null($this->baseDir) )
			throw SorryInvalidTask::path($this->baseDir);


		$this->service = new ServiceContainer($this,
			new ResolveContainer($this)
		);


		$this->service->loadConfig('services');

		// fiber, config, service ready;
		$this->_init();

		// << bootstrap.php

		$this->config->loadConfig('config');

		// services loaded, database connected, stacks loaded
		// $this->service->get('database')->connect();


	}

	public function __destruct()
	{
		$this->_finished();
	}


	public function __call( string $method, array $arguments )
	{
		if( ! $this->service->has($method) ) throw SorryInvalidTask::service($method);

		return $this->service->getService($method);
	}


	public function path( string $file ) : string
	{
		return $this->baseDir.$file;
	}



	/**
	 * Gets the service.
	 *
	 * @return     object  The service.
	 */
	public function getService() : object
	{
		return $this->service->get(...func_get_args());
	}

	/**
	 * Alias to getService
	 *
	 * @return     object  The service
	 */
	public function service() : object
	{
		return $this->getService(...func_get_args());
	}

	/**
	 * Alias to getService
	 *
	 * @return     object  The service
	 */
	public function get( string $service ) : object
	{
		return $this->getService( $service );
	}



	/**
	 * Gets the service as variable.
	 *
	 * @param      string  $variable  The variable
	 *
	 * @return     object  ( description_of_the_return_value )
	 */
	public function __get( string $variable ) : object
	{
		return $this->service->get($variable);
	}



	/**
	 * Adds a service.
	 */
	public function addService() : void
	{
		$this->service->set(...func_get_args());
	}

	/**
	 * Alias to addService
	 */
	public function set( string $name, $class) : void
	{
		$this->addService( $name, $class);
	}



	/**
	 * Determines if service exists.
	 *
	 * @return     boolean  True if service, False otherwise.
	 */
	public function hasService() : bool
	{
		return $this->service->has(...func_get_args());
	}

	/**
	 * Alias to hasService
	 */
	public function has( string $service ) : bool
	{
		return $this->hasService( $service );
	}


	public function feature( string $feature ) : bool
	{
		return array_key_exists($feature, $this->features)
			? $this->features[$feature]
			: false;
	}

	public function enable( string $feature )
	{
		$this->features[$feature] = true;
	}

	public function disable( string $feature )
	{
		$this->features[$feature] = false;
	}


	public function load()
	{
		try {
			$this->_load();
		}
		catch( SorryInvalidRoute $e ) {
			Errorpage::create($e)->get404();
		}
		catch( FrameworkException $e ) {
			Errorpage::create($e)->get500();
		}
		catch( Exception $e ) {
			Errorpage::create($e)->get500();
		}
	}


	private function _load()
	{
		if ( $this->ready ) // call me once!
			throw SorryInvalidTask::call(static::class, 'load');


		$this->_ready();


		// page loaded but not yet output
		// $this->service->get('router')->render();
		// $this->service->get('template')->render();
		// for services as service, service implements RunnableInterface, service->run()
		$this->_render();


		// finished | destruct
		$this->_finished();

		// something went bad
		//$this->_failed();
		
		$this->ready = true;

	}

	public function base() :string
	{
		return $this->baseDir;
	}

	public function app() :string
	{
		return $this->appDir;
	}

	private function _init() : void
	{
		$this->requireFile($this->task->base().'defaults/init.php');
	}


	// services loaded, database connected, stacks loaded
	private function _ready() : void
	{
		$this->loadConfig('ready');
	}

	// template loaded but not output
	private function _render() : void
	{
		$this->loadConfig('routes');
		$this->get('router')->resolve();
	}


	private function _finished() : void
	{
		$this->loadConfig('finished');
	}
	private function _failed() : void
	{
		$this->loadConfig('failed');
	}

}
