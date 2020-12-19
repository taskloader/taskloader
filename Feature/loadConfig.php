<?php namespace TaskFiber\Feature;


trait loadConfig {
  use requireFile;

  
  public function loadConfig( string $name ) : void
  {
    $this->requireFile($this->fiber->base().'defaults/'.$name.'.php');
    $this->requireFile($this->fiber->base().'app/'.$name.'.php');
  }
}
