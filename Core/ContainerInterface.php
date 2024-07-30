<?php namespace TaskLoader\Core;

interface ContainerInterface {
	public function get( string $name );
	public function has( string $name ) : bool;
	public function set( string $name, $value );
}