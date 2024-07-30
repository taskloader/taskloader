<?php namespace TaskLoader\Core;

use TaskLoader\TaskLoader;
use \ReflectionClass, \ReflectionParameter;

class ResolveContainer implements ContainerInterface {
	private array $allocate = [];
	protected TaskLoader $task;

	public function __construct( TaskLoader $task )
	{
		$this->task = $task;
	}

	public function __invoke( string $key = null, string $value = null )
	{
		if ( is_null($key) )
			return $this;

		if ( is_null($value) )
			return $this->get($key);

		$this->add($key, $value);
	}

	public function get( string $name )
	{
		return $this->getInstance( $name );
	}

	public function has( string $name ) : bool
	{
		return array_key_exists($name, $this->allocate);
	}

	public function set( string $name, $value ) : void
	{
		$this->allocate[$name] = $value;
	}

	public function addClass( string $class, string $newClass ) : void
	{
		$this->set($class, $newClass);
	}

	public function getInstance( string $class ) : object
	{
		// Check if we need to resolve a different class
		$this->resolveClass($class);

		$reflector = new ReflectionClass( $class );

		if ( ! $reflector->isInstantiable() )
			throw SorryInvalidContainer::call(static::class, $class);


		$constructor = $reflector->getConstructor();

		if ( is_null($constructor) )
			return new $class;

		$parameters = $constructor->getParameters();
		$dependencies = $this->getDependencies( $parameters );

		return $reflector->newInstanceArgs( $dependencies );
	}

	protected function getDependencies( array $parameters ) : array
	{
		$dependencies = array();

		foreach( $parameters as $parameter ) {
			$dependency = $parameter->getType() && ! $parameter->getType()->isBuiltin()
				? new ReflectionClass($parameter->getType()->getName())
				: null;

			if ( is_null($dependency) )
				$dependencies[] = $this->resolveScalar($parameter);

			elseif ( $this->task->service->contains($dependency->name) )
				$dependencies[] = $this->task->service->from($dependency->name);

			else
				$dependencies[] = $this->get( $dependency->name );
		}

		return $dependencies;
	}

	protected function resolveClass( string &$class ) : void
	{
		if ( $this->has($class) )
			$class = $this->allocate[$class];
	}

	protected function resolveScalar( ReflectionParameter $parameter )
	{
		// If a default value is available return the value
		if ( $parameter->isDefaultValueAvailable() )
			return $parameter->getDefaultValue();

		throw new \InvalidArgumentException("Could not resolve default value for ".$parameter);
	}
}
