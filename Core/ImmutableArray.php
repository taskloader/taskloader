<?php namespace TaskLoader\Core;

class ImmutableArray {
	private array $allocate = [];

	public function __construct( array $items )
	{
		$this->allocate = $items;
	}

	public function __get( string $name )
	{
		return $this->allocate[$name] ?: null;
	}
}