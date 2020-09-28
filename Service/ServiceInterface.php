<?php namespace TaskFiber\Service;


interface ServiceInterface {
	public function instance();
	public function invoke();
}