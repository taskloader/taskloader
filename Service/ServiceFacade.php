<?php namespace TaskFiber\Service;


abstract class ServiceFacade {
	public static function instance() : ServiceInterface
	{
		$className = strtolower(static::class);

		return \TaskFiber\Fiber::service($className);
	}

	public static function __callStatic( string $method, array $parameters )
	{
		return call_user_method_array([self::instance(), $method], $parameters );
	}
}