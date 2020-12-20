<?php namespace TaskFiber\Core;
use TaskFiber\TaskFiber;



class ServiceContainer implements ContainerInterface {
	private array $classes = [];
	private array $instances = [];
	protected TaskFiber $fiber;

	use \TaskFiber\Feature\loadConfig;

	public function __construct( TaskFiber $fiber, ResolveContainer $resolve )
	{
		$this->fiber = $fiber;
		$this->store('fiber', TaskFiber::class, $fiber);
		$this->store('service', ServiceContainer::class, $this);
		$this->store('resolve', ResolveContainer::class, $resolve);
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
		$this->classes[$name] = $class;
	}

	/**
	 * Alias to addService
	 */
	public function set( string $name, $value )
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


		if ( ! array_key_exists($service, $this->instances) )
			$this->instances[$service] = $this->get('resolve')->get($this->classes[$service]);

		return $this->instances[$service];
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
		return array_key_exists($service, $this->classes);
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


	private function store( string $name, string $class, object $instance ) : void
	{
			$this->instances[$name] = $instance;
			$this->classes[$name] = $class;
	}

	public function contains( string $class ) : bool
	{
		return in_array($class, $this->classes);
	}

	public function getClass( string $class ) : string
	{
		return array_search($class, $this->classes);
	}

	public function from( string $class ) : object
	{
		return $this->get($this->getClass($class));
	}


}
