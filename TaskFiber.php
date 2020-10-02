<?php namespace TaskFiber;
use TaskFiber\Core\ContainerInterface;
use TaskFiber\Core\ServiceContainer;
use TaskFiber\Core\ResolveContainer;

class TaskFiber implements ContainerInterface {
	private ServiceContainer $service;
	private string $baseDir;



	/**
	 * Constructs a new instance.
	 */
	public function __construct( string $path )
	{
		$this->baseDir = realpath($path);

		if ( is_null($this->baseDir) )
			throw SorryInvalidFiber::path($this->baseDir);



		$this->service = new ServiceContainer($this,
			new ResolveContainer($this)
		);
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


}