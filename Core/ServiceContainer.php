<?php namespace TaskFiber\Core;

use \TaskFiber\Core\ServiceException as Exception;


class ServiceContainer {
	private array $services = [];
	private ResolveContainer $resolver;

	public function __construct( ResolveContainer &$resolver )
	{
		$this->resolver = $resolver;
	}

	/**
	 * Adds a singleton instance to takslist
	 *
	 * @param      string  $name   The name
	 * @param      string  $class  The class
	 */
	public function bindService( string $name, string $class ) : Process
	{
		return $this->addService($name, new ProcessSingleService($this->resolver, $class));
	}


	/**
	 * Alias to bindService
	 *
	 * @param      string  $name   The name
	 * @param      string  $class  The class
	 */
	public function bind( string $name, string $class ) : Process
	{
		return $this->bindService($name, $class);
	}



	/**
	 * Adds a multi instance to servicelist
	 *
	 * @param      string  $name   The name
	 * @param      string  $class  The class
	 */
	public function attachService( string $name, string $class ) : Process
	{
		return $this->addService($name, new ProcessMultiService($this->resolver, $class));
	}



	/**
	 * Alias to attachService
	 *
	 * @param      string  $name   The name
	 * @param      string  $class  The class
	 */
	public function attach( string $name, string $class ) : Process
	{
		return $this->attachService($name, $class);
	}



	public function addService( string $name, Process $process ) : Process
	{
		$this->services[$name] = $process;

		return $process;
	}



	/**
	 * Get a service instance from service list
	 *
	 * @param      string    $name     The name
	 * @param      ServiceProcess  $process  The process
	 */
	public function get( string $name )
	{
		if ( ! $this->has($name) )
			throw Exception::invalidService($name);

		return $this->services[$name]->process();
	}


	public function has( string $service ) : bool
	{
		return array_key_exists($service, $this->services);
	}


	public function resolve( string $class, string $replace ) : void
	{
		$this->resolver->register($class, $replace);
	}


}
