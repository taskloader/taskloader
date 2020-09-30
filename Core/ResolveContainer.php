<?php namespace TaskFiber\Core;

use \TaskFiber\FiberException as Exception;
use \ReflectionClass, \ReflectionParameter;

class ResolveContainer extends ContainerProvider {

	public function addClass( string $class, string $newClass ) : void
	{
		$this->add($class, $newClass);
	}


	protected function process( $class ) {
		return $this->getInstance( $class );
	}

	public function getInstance( string $class ) : object
	{
		// Check if we need to resolve a different class
		$this->resolveClass($class);

		$reflector = new ReflectionClass( $class );

		if ( ! $reflector->isInstantiable() )
			throw Exception::badMethodCall("$class is not instantiable");
		

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
			$dependency = $parameter->getClass();

			if ( is_null($dependency) )
				$dependencies[] = $this->resolveScalar();

			else
				$dependencies[] = $this->resolve( $dependency->name );
		}

		return $dependencies;
	}

	protected function resolveClass( string &$class ) : void
	{
		if ( $this->has($class) )
			$class = $this->get($class);
	}

	protected function resolveScalar( ReflectionParameter $parameter )
	{
		// If a default value is available return the value
		if ( $parameter->isDefaultValueAvailable() )
			return $parameter->getDefaultValue();

		throw Exception::invalidArgumentException("Could not resolve default value");
	}
}