<?php
namespace TaskFiber\Service;

class MultiServiceProcess extends ServiceProcess {
	private $instance;
	
	public function process()
	{
		return $this->build();
	}
}