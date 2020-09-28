<?php namespace TaskFiber;

abstract class ServiceProvider implements Service\ServiceInterface {
	public function __invoke()
	{
		return $this->invoke(func_get_args());
	}
}