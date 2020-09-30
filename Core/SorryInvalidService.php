<?php namespace TaskFiber\Core;

class SorryInvalidService extends SorryInvalidFiber {

   public static function name( $service, $code = 0, \Exception $previous = null ) {
      $message = sprintf(
         'Service %s does not exist.',
         $service
      );
 
      return new static( $message, $code, $previous );
   }
}