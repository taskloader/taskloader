<?php namespace TaskFiber\Service;

abstract class ServiceProvider implements ServiceInterface {
	public function __invoke()
	{
		return $this->invoke(func_get_args());
	}
}