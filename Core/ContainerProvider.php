<?php namespace TaskLoader\Core;
use TaskLoader\TaskLoader;

abstract class ContainerProvider implements ContainerInterface {
	protected TaskLoader $task;
	protected array $allocate = [];

	//use \TaskLoader\Feature\requireFile;
	use \TaskLoader\Feature\loadConfig;


	final public function __construct( TaskLoader $task )
	{
		$this->task = $task;
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

	
	public function asArray() : array
	{
		return $this->allocate;
	}

}
