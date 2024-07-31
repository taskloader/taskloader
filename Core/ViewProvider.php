<?php namespace TaskLoader\Core;


class ViewProvider extends ContainerProvider
{
    public function load( string $filename )
    {
        $file = $this->task->app().'templates/'.$filename.'.php';

        if ( !file_exists($file) ) throw SorryInvalidView::templateNotFound();

        extract($this->task->service->asArray());
        extract($this->allocate);

        include $file;
    }
}