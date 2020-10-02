<?php namespace TaskFiber\Core;


abstract class ServiceFacade {
	private function __clone() {}
	public function __wakeup() {
		throw new \Exception("Cannot unserialize core", 1);
	}
	private function __construct() {}


	public static function instance()
	{
		$service = strtolower(
			(new \ReflectionClass(static::class))->getShortName()
		);

		if ( \TaskFiber\Fiber::hasService( $service ) )
			return \TaskFiber\Fiber::getService($service);

		throw SorryInvalidService::name($service);
	}

	public static function __callStatic( string $method, array $parameters )
	{
		//return self::instance()->$method(...$parameters);
		return call_user_func_array([self::instance(), $method], $parameters );
	}
}