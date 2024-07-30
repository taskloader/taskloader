<?php
namespace TaskLoader;
use TaskLoader\Core\SorryInvalidTask;

/**
 * Holds the singleton for the main Thread
 */
class Task {
	private static $instance;


	private function __clone() {}
	public function __wakeup() {
		throw new FiberException("Cannot unserialize core", 1);
	}
	private function __construct() {}



	/**
	 * Returns a Thread instance
	 *
	 * @return     Thread  The core of the application
	 */
	public static function instance() : TaskLoader
	{
		if ( ! self::$instance )
			self::$instance = new TaskLoader(__DIR__);

		return self::$instance;
	}


	/**
	 * Diverts all calls to the Thread instance
	 *
	 * @param      string  $method     The method
	 * @param      array   $arguments  The arguments
	 *
	 * @return     Process|NULL
	 */
	public static function __callStatic( string $method, array $arguments )
	{
		if ( ! is_callable([self::instance(), $method]) )
			throw SorryInvalidFiber::method(static::class, $method);

		return call_user_func_array([self::instance(), $method], $arguments);
	}

}