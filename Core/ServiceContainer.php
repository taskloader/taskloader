<?php namespace TaskFiber\Core;



class ServiceContainer implements ContainerInterface {
	private array $services = [];

	public function __construct()
	{
		$this->services['service'] = $this;
		$this->services['resolve'] = new ResolveContainer($this);
	}


	/**
	 * Adds a service provider.
	 *
	 * @param      string           $name     The name
	 * @param      ProcessProvider  $process  The process
	 *
	 * @return     ProcessProvider  The process provider.
	 */
	public function addService( string $name, string $class ) : void
	{
		$this->services[$name] = $class;
	}

	/**
	 * Alias to addService
	 */
	public function add( string $name, $value )
	{
		return $this->addService(...func_get_args());
	}



	/**
	 * Get a service instance from service list
	 *
	 * @param      string           $service  The service
	 */
	public function getService( string $service )
	{
		if ( ! $this->has($service) )
			throw SorryInvalidService::name($service);

		$service = &$this->services[$service];

		if ( gettype($service) == 'string' )
			$service = $this->get('resolve')->get($service);

		return $service;
	}

	/**
	 * Alias to getService
	 */
	public function get( string $name )
	{
		return $this->getService(...func_get_args());
	}



	/**
	 * Determines if service is registered.
	 *
	 * @param      string   $service  The service
	 *
	 * @return     boolean  True if service, False otherwise.
	 */
	public function hasService( string $service ) : bool
	{
		return array_key_exists($service, $this->services);
	}

	/**
	 * alias to hasService
	 *
	 * @param      string   $service  The service
	 *
	 * @return     boolean  Service registered
	 */
	public function has( string $name ) : bool
	{
		return $this->hasService(...func_get_args());
	}


}
