<?php namespace TaskLoader\Core;

use ServiceException as Exception;
use \ReflectionClass, \ReflectionParameter;

class ServiceResolver {
	private array $resolve = [];


	public function register( string $class, string $newClass )
	{
		$this->resolve[$class] = $newClass;
	}

	public function resolve( string $class )
	{
		// Check if we need to resolve a different class
		$this->resolveClass($class);

		$reflector = new \ReflectionClass( $class );

		if ( ! $reflector->isInstantiable() )
			throw Exception::badMethodCall("$class is not instantiable");
		

		$constructor = $reflector->getConstructor();

		if ( is_null($constructor) )
			return new $class;

		$parameters = $constructor->getParameters();
		$dependencies = $this->getDependancies( $parameters );

		return $reflector->newInstanceArgs( $dependencies );
	}

	protected function getDependencies( array $parameters )
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

	protected function resolveClass( string &$class )
	{
		if ( array_key_exists($class, $this->resolve) )
			$class = $this->resolve[$class];
	}

	protected function resolveScalar( ReflectionParameter $parameter )
	{
		// If a default value is available return the value
		if ( $parameter->isDefaultValueAvailable() )
			return $parameter->getDefaultValue();

		throw Exception::invalidArgumentException("Could not resolve default value");
	}
}