<?php
namespace TaskFiber\Core;

class ProcessMultiService extends Process {
	private $instance;
	
	public function process()
	{
		return $this->build();
	}
}