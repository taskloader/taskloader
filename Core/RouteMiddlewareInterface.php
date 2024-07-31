<?php

namespace TaskLoader\Core;

interface RouteMiddlewareInterface {
	public function allowed() : bool;
}