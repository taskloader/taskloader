<?php namespace TaskFiber\Core;

class SorryInvalidRoute extends SorryInvalidFiber {

   public static function name( $route, $code = 0, \Exception $previous = null ) {
      $message = sprintf(
         'Route %s does not exist.',
         $route
      );
 
      return new static( $message, $code, $previous );
   }
}