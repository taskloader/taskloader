<?php namespace TaskFiber\Core;

class ImmutableArray {
	private $allocate;

	public function __construct( array $items )
	{
		$this->allocate = $items;
	}

	public function __get( string $name )
	{
		return $this->allocate[$name] ?: null;
	}
}