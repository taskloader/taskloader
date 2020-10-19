<?php
declare(strict_types = 1);
namespace TaskFiber\Core;
use TaskFiber\TaskFiber;


/**
 * This class describes a router provider.
 */
class RouterProvider {
	private TaskFiber $fiber;
	private RequestProvider $request;

	private array $routeNames = [];
	private array $routeGroups = [];
	private string $routePrefix = '';

	private array $domainRoutes = [];

	private array $supportedMethods = [
		'GET', 'POST', 'PUT', 'DELETE'
	];

	protected array $filters = [
		':name' => '([a-zA-Z]+)',
		':id' => '([0-9]+)',
		':alpha'  => '([a-zA-Z0-9-_]+)',
		':any' => '(.*)'
	];



	/**
	 * Constructs a new instance.
	 *
	 * @param      iRequest  $request  The request
	 */
	public function __construct( TaskFiber $fiber, RequestProvider $request )
	{
		$this->fiber = $fiber;
		$this->request = $request;

		foreach ( $this->supportedMethods  as $method )
			$this->{$method} = array();
	}




	/**
	 * Stores route if method is supported
	 *
	 * @param      string  $method       The method
	 * @param      array   $arguments  The arguments
	 */
	public function __call( string $method = null, array $arguments = null ) : RouteInterface
	{

		if ( count($arguments) != 2 )
			throw SorryInvalidRoute::requestMethod( $method );



		// Throw exception on invalid method
		if ( ! $this->supportsMethod( $method ) )
			throw SorryInvalidRoute::requestMethod( $method );



		$method = strtolower($method);

		list( $route, $handler ) = $arguments;


		// Store the route
		return $this->store($method, $route, $handler);
	}



	/**
	 * Determines if method is implemented
	 *
	 * @param      string   $methodName  The method name
	 *
	 * @return     boolean  True if method, False otherwise.
	 */
	protected function supportsMethod( string $methodName ) : bool
	{
		return in_array(
			strtoupper($methodName),
			$this->supportedMethods
		);
	}



	/**
	 * Stores route if method is supported
	 *
	 * @param      string  $method       The method
	 * @param      array   $arguments  The arguments
	 */
	protected function store( string $method, string $route, \Closure $handler ) : RouteInterface
	{
		$route = $this->routePrefix.$route;
		$route = strtr($route, $this->filters);
		$routerItem = new RouteProvider($route, $handler, $this);

		// Store the route
		$this->{$method}[$route] = $routerItem;

		return $routerItem;
	}



	private function resolve() : void
	{
		$method = strtolower($this->request->requestMethod);
		$domain = $this->request->domain;
		$request = $this->request->requestUri;

		// Resolve domain based routes first
		$this->resolveRoute('domain', $domain);

		// Resolve group routes first
		$this->loadGroupRoutes();

		// Resolve current route
		if ( ! $this->resolveRoute($method, $request) )
			$this->routeNotFound();

	}



	/**
	 * Resolves route based on method and uri
	 *
	 * @param      string   $method   The method
	 * @param      string   $request  The request
	 *
	 * @return     boolean  route found
	 */
	protected function resolveRoute( string $method, string $request ) : bool
	{
		if ( property_exists($this, $method) )
			return $this->matchRoute( $request, $this->{$method} );

		return false;
	}

	private function loadGroupRoutes()
	{
		foreach( $this->routeGroups as $handler )
			$handler->call();
	}




	/**
	 * Finds and calls a matching route
	 *
	 * @param      string   $route   The route
	 * @param      array    $routes  The routes
	 *
	 * @return     boolean  route found
	 */
	private function matchRoute( string $route, array $routes ) : bool
	{
		foreach( $routes as $routeUri => $routeItem ) {
			if( preg_match('#^/?'.$routeUri.'/?$#', $route, $this->parameters)) {
				array_shift($this->parameters);
				// Fail when route explicitly returns false
				if( $routeItem->call( $this->parameters ) === false )
					return false;

				return true;
			}
		}

		return false;
	}


	/**
	 * Displays 404 page
	 */
	public function routeNotFound() : void
	{
		if ( property_exists($this, 'get') and array_key_exists('error404', $this->get) )
			$this->get['error404']->call();

		else throw SorryInvalidRoute::routeNotFound( $this->request );
	}



	/**
	 * Resolves routes on class destruction
	 */
	public function __destruct()
	{
		$this->resolve();
	}


}