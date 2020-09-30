<?php namespace TaskFiber\Core;


abstract class ContainerProvider implements ContainerInterface {
	private array $allocate = [];

	final public function __invoke( string $key = null, string $value = null )
	{
		if ( is_null($key) )
			return $this;

		if ( is_null($value) )
			return $this->get($key);

		$this->add($key, $value);
	}

	final public function get( string $name )
	{
		return $this->process(
			$this->allocate[$name]
		);
	}

	final public function has( string $name ) : bool
	{
		return array_key_exists($name, $this->allocate);
	}

	final public function add( string $name, $value ) : void
	{
		if ( ! $this->isValid($value) )
			throw SorryInvalidContainer::parameter();

		$this->validate($value);
		$this->allocate[$name] = $value;
	}

	protected function validate( &$value) : void {}

	protected function isValid( $value ) : bool
	{
		return true;
	}

	protected function process( $value )
	{
		return $value;
	}
}