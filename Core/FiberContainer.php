<?php namespace TaskFiber\Core;

class FiberContainer implements ContainerInterface {
	private ServiceContainer $service;



	/**
	 * Constructs a new instance.
	 */
	public function __construct()
	{
		$this->service = new ServiceContainer();
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
	 * Adds a service.
	 */
	public function addService() : void
	{
		$this->service->add(...func_get_args());
	}

	/**
	 * Alias to addService
	 */
	public function add( string $name, $class) : void
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