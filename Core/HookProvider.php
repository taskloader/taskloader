<?php namespace TaskLoader\Core;

class HookProvider {
    private array $hooks = [];

    /**
     * Fires all hooks for named
     * 
     * @param string Name of the hook
     * @param array|null Parameters for the hook callable
     */
    public function fire( string $hookname, $params = null )
    {
        if ( array_key_exists($hookname, $this->hooks) ) {
            foreach( $this->hooks[$hookname] as $function ) {
                call_user_func_array($function, array(&$params));
            }
        }
    }


    /**
     * Adds hook function for named
     * 
     * @param string Name of the hook
     * @param object Function for hook
     */
    public function add(string $hookname, object $function)
    {
        $this->hooks[$hookname][] = $function;
    }
}