<?php namespace TaskFiber;
use FiberException as Exception;
use TaskFiber\Service\ServiceContainer;
use TaskFiber\Service\ServiceProvider;
use TaskFiber\Resolve\ResolveContainer;

class FiberContainer {
	private ServiceContainer $service;
	private MacroService $macro;

	public function __construct()
	{
		$this->resolver = new ResolveContainer();
		$this->service = new ServiceContainer( $this->resolver );
	}

	public function __call( string $method, array $parameters )
	{
		if ( $this->service->has($method) )
			return $this->service->get($method)->invoke(func_get_args());

		throw Exception::badFunctionCall( static::class, $method );
	}

	public function bind( string $service, string $class ) : void
	{
		$this->service->bind($service, $class);
	}

	public function attach( string $service, string $class ) : void
	{
		$this->service->attach($service, $class);
	}

	public function service( string $service ) : ServiceProvider
	{
		return $this->service->get($service);
	}

	public function resolve( string $class, string $replace )
	{
		$this->service->resolve($class, $replace);
	}

	public function alias( string $fiberClass, string $alias )
	{
		$fiberClass = 'TaskFiber\\'.$fiberClass;

		echo $fiberClass;
		class_alias($fiberClass, $alias);
	}
}