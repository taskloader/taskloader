<?php namespace TaskFiber\Core;
use TaskFiber\TaskFiber;

abstract class ContainerProvider implements ContainerInterface {
	protected TaskFiber $fiber;
	protected array $allocate = [];

	//use \TaskFiber\Feature\requireFile;
	use \TaskFiber\Feature\loadConfig;


	final public function __construct( TaskFiber $fiber )
	{
		$this->fiber = $fiber;
	}


	final public function __invoke( string $key = null, string $value = null )
	{
		if ( is_null($key) )
			return $this;

		if ( is_null($value) )
			return $this->get($key);

		$this->set($key, $value);
	}


	public function get( string $key )
	{
		return $this->allocate[$key];
	}

	public function set( string $key, $value) : void
	{
		$this->allocate[$key] = $value;
	}


	public function has( string $key ) : bool
	{
		return array_key_exists($key, $this->allocate);
	}

}
