<?php namespace TaskLoader\Feature;


trait loadConfig {
  use loadFile;


  public function loadConfig( string $name ) : void
  {
    $task = $this->task;
    extract($this->task->service->asArray());
    
    if ( $task->hasService('router') ) $router = $task->router;

    if ( file_exists($this->task->base().'defaults/'.$name.'.php') )
      include_once ($this->task->base().'defaults/'.$name.'.php');

    if ( file_exists($this->task->app().$name.'.php') )
      include_once ($this->task->app().$name.'.php');
  }
}
