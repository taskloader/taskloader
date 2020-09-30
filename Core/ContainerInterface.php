<?php namespace TaskFiber\Core;

interface ContainerInterface {
	public function get( string $name );
	public function has( string $name ) : bool;
	public function add( string $name, $value );
}