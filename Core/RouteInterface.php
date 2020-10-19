<?php

namespace TaskFiber\Core;

interface RouteInterface {
	public function name( string $name );
	public function route();
	public function middleware( string $class );
	public function call( array $parameters = [] );
}