<?php namespace TaskLoader\Core;


interface ServiceInterface {
	public function instance();
	public function invoke();
}