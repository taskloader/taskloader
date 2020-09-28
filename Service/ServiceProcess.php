<?php
namespace TaskFiber\Service;
use \TaskFiber\Resolve\ResolveContainer as Resolver;

abstract class ServiceProcess implements ServiceProcessInterface {
	protected Resolver $resolver;
	protected string $class;

	public function __construct( Resolver $resolver, string $class )
	{
		$this->resolver = $resolver;
		$this->class = $class;
	}


	protected function build()
	{
		return $this->resolver->resolve($this->class);
	}

}