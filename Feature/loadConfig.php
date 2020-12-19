<?php namespace TaskFiber\Feature;


trait loadConfig {
  use loadFile;


  public function loadConfig( string $name ) : void
  {
    $this->loadFile($this->fiber->base().'defaults/'.$name.'.php');
    $this->loadFile($this->fiber->base().'app/'.$name.'.php');
  }
}
