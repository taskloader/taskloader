<?php namespace TaskFiber\Core;


interface ServiceInterface {
	public function instance();
	public function invoke();
}