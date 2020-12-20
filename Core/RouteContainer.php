<?php
declare(strict_types = 1);
namespace TaskFiber\Core;
use TaskFiber\TaskFiber;


/**
 * This class describes a router provider.
 */
class RouteContainer {
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


	use \TaskFiber\Feature\loadConfig;



	/**
	 * Constructs a new instance.
	 *
	 * @param      \TaskFiber\TaskFiber  $fiber  The fiber
	 */
	public function __construct( TaskFiber $fiber )
	{
		$this->fiber = $fiber;
		$this->request = $fiber->request;

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


	/**
	 * Sotre domain route
	 *
	 * @param      string    $route    The route
	 * @param      \Closure  $handler  The handler
	 *
	 * @return     <type>    ( description_of_the_return_value )
	 */
	public function domain( string $route, \Closure $handler )
	{
		return $this->store('domain', $route, $handler);
	}


	/**
	 * Store a route group
	 *
	 * @param      \Closure  $handler  The handler
	 *
	 * @return     <type>    ( description_of_the_return_value )
	 */
	public function group( \Closure $handler )
	{
		return $this->store('group', (string) count($this->routeGroups), function() use ($handler) {
			$handler->call($this->parameters);
		});
	}


	/**
	 * Store prefixed route group
	 *
	 * @param      string    $route    The route
	 * @param      \Closure  $handler  The handler
	 *
	 * @return     <type>    ( description_of_the_return_value )
	 */
	public function prefix( string $route, \Closure $handler )
	{
		return $this->store('group', "$route/(.*)", function() use ($route, $handler) {
			$this->loadPrefixRoute($route, $handler); // Save to run it later
		});
	}



	/**
	 * Loads a prefix route.
	 *
	 * @param      string    $prefix   The prefix
	 * @param      \Closure  $handler  The handler
	 */
	private function loadPrefixRoute( string $prefix, \Closure $handler )
	{
		$this->routePrefix = $prefix;

		$handler->call($this); // Extract route handler

		$this->routePrefix = '';
	}



	/**
	 * Load view from route
	 *
	 * @param      string  $route  The route
	 * @param      string  $view   The view
	 *
	 * @return     <type>  ( description_of_the_return_value )
	 */
	public function view( string $route, string $view )
	{
		return $this->store('get', $route, function() use($view) {
			return $this->fiber->get('view')->view($view, $this->parameters);
		});
	}



	/**
	 * Loads controler from route
	 *
	 * @param      string    $route       The route
	 * @param      $|string  $controller  The controller
	 */
	public function controller( string $route, string $controller )
	{
		$reflection  = new \ReflectionClass($controller);

		foreach( $this->supportedMethods as $method ) {

			if ( $reflection->hasMethod( $method) ) {
				$method = strtolower($method);

				$this->store($method, $route, function() use( $controller )
				{
					$controller = new $controller();
					$method = strtolower($this->request->requestMethod);

					$controller->{$method}(...$this->parameters);
				});
			}
		}
	}



	/**
	 * Adds a named route.
	 *
	 * @param      string          $name   The name
	 * @param      RouteInterface  $route  The route
	 */
	public function addNamedRoute( string $name, RouteInterface $route )
	{
		$this->namedRoutes[$name] = $route;
	}



	/**
	 * Add new filter pattern
	 *
	 * @param      string  $name     The name
	 * @param      string  $pattern  The pattern
	 */
	public function pattern( string $name, string $pattern )
	{
		$this->filers[":$name"] = $pattern;
	}



	/**
	 * Resolve routes
	 */
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

		else throw SorryInvalidRoute::notFound( $this->request->requestUri );
	}


}
