<?php
namespace TaskFiber\Core;

abstract class Process implements ProcessInterface {
	protected ResolveContainer $resolver;
	protected string $class;

	public function __construct( ResolveContainer $resolver, string $class )
	{
		$this->resolver = $resolver;
		$this->class = $class;
	}


	protected function build()
	{
		return $this->resolver->resolve($this->class);
	}

}