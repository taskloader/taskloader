<?php namespace TaskLoader\Feature;


trait loadConfig {
  use loadFile;


  public function loadConfig( string $name ) : void
  {
    $this->loadFile($this->task->base().'defaults/'.$name.'.php');
    $this->loadFile($this->task->base().'../app/'.$name.'.php');
  }
}
