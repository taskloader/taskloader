<?php

namespace TaskLoader\Core;

class RouteProvider implements RouteInterface {
	private string $name;
	private string $route;
	private \Closure $handler;
	private RouteContainer $router;
	private array $middleware = [];

	
	public function __construct( string $route, \Closure $handler, RouteContainer $router )
	{
		$this->name = '';
		$this->route = $route;
		$this->handler = $handler;
		$this->router = $router;
	}


	public function name( string $name = null )
	{
		if ( is_null($name) )
			return $this->name;

		$this->name = $name;
		$this->router->addNamedRoute($name, $this);

		return $this;
	}

	public function route() : string
	{
		return $this->route;
	}


	public function middleware( string $class ) : self
	{
		$reflection = new \ReflectionClass($class);

		if ( ! $reflection->implementsInterface('\TaskLoader\Core\RouteMiddlewareInterface') )
			throw SorryInvalidRoute::middleware($class);

		// if $reflection instance_of middleware ...
		$this->middleware[] = $class;


		return $this;
	}


	public function call( array $parameters = [] )
	{

		foreach($this->middleware as $middlewareName) {
			$middleware = new $middlewareName( $this );

			if ( ! $middleware->allowed() ) return false;
		}


		return $this->handler->call($this->router, ...$parameters);
	}
}