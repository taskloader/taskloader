<?php namespace TaskFiber\Core;


abstract class ServiceFacade {
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
		return self::instance()->$method(...$parameters);
		//call_user_func_array([self::instance(), $method], $parameters );
	}
}