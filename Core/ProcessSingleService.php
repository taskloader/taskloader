<?php
namespace TaskFiber\Core;

class ProcessSingleService extends Process {
	private $instance;
	
	public function process()
	{
		if ( is_null($this->instance) )
			$this->instance = $this->build();

		return $this->instance;
	}
}