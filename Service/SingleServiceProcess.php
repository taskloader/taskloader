<?php
namespace TaskFiber\Service;

class SingleServiceProcess extends ServiceProcess {
	private $instance;
	
	public function process()
	{
		if ( is_null($this->instance) )
			$this->instance = $this->build();

		return $this->instance;
	}
}