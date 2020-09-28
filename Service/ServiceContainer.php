<?php namespace TaskFiber\Service;

use \TaskFiber\Resolve\ResolveContainer as Resolver;
use \TaskFiber\Service\ServiceException as Exception;


class ServiceContainer {
	private array $services = [];
	private Resolver $resolver;

	public function __construct( Resolver &$resolver )
	{
		$this->resolver = $resolver;
	}

	/**
	 * Adds a singleton instance to takslist
	 *
	 * @param      string  $name   The name
	 * @param      string  $class  The class
	 */
	public function bindService( string $name, string $class ) : ServiceProcess
	{
		return $this->addService($name, new SingleServiceProcess($this->resolver, $class));
	}


	/**
	 * Alias to bindService
	 *
	 * @param      string  $name   The name
	 * @param      string  $class  The class
	 */
	public function bind( string $name, string $class ) : ServiceProcess
	{
		return $this->bindService($name, $class);
	}



	/**
	 * Adds a multi instance to servicelist
	 *
	 * @param      string  $name   The name
	 * @param      string  $class  The class
	 */
	public function attachService( string $name, string $class ) : ServiceProcess
	{
		return $this->addService($name, new MultiServiceProcess($this->resolver, $class));
	}



	/**
	 * Alias to attachService
	 *
	 * @param      string  $name   The name
	 * @param      string  $class  The class
	 */
	public function attach( string $name, string $class ) : ServiceProcess
	{
		return $this->attachService($name, $class);
	}



	public function addService( string $name, ServiceProcess $process ) : ServiceProcess
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
