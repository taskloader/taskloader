<?php namespace TaskFiber;
use TaskFiber\Core\ContainerInterface;
use TaskFiber\Core\ServiceContainer;
use TaskFiber\Core\ResolveContainer;

class TaskFiber implements ContainerInterface {
	private ServiceContainer $service;
	private BootstrapProvider $bootstrap;
	private array $features = [];
	private string $baseDir;
	private bool $ready = false;

	use Feature\requireFile;



	/**
	 * Constructs a new instance.
	 */
	public function __construct( string $path )
	{
		$this->baseDir = realpath($path).\DIRECTORY_SEPARATOR;

		if ( is_null($this->baseDir) )
			throw SorryInvalidFiber::path($this->baseDir);

		$this->requireFile('boot.php');


		$this->service = new ServiceContainer($this,
			new ResolveContainer($this)
		);

		$this->config->loadConfig('config');

		// fiber, config, service ready;
		$this->_init();

		// << bootstrap.php

		// services loaded, database connected, stacks loaded
		$this->service->loadConfig('services');
		// $this->service->get('database')->connect();
		// $this->service->get('stacks')->load();

	}

	public function __destruct()
	{
		$this->_finished();
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
		if ( $this->ready ) // call me once!
			throw SorryInvalidFiber::call(static::class, 'load');


		$this->_ready();


		// page loaded but not yet output
		// $this->service->get('router')->render();
		// $this->service->get('template')->render();
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

	private function _init() : void
	{
		$this->requireFile('init.php');
	}


	// services loaded, database connected, stacks loaded
	private function _ready() : void
	{
		$this->requireFile('ready.php');
	}

	// template loaded but not output
	private function _render() : void
	{
		$this->requireFile('ready.php');
	}


	private function _finished() : void
	{
		$this->requireFile('finished.php');
	}
	private function _failed() : void
	{
		$this->requireFile('failed.php');
	}

}
