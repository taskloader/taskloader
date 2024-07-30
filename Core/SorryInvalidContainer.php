<?php namespace TaskLoader\Core;

class SorryInvalidContainer extends SorryInvalidTask {

   public static function value( $name, $code = 0, \Exception $previous = null ) {
      $message = sprintf(
         'Value %s is not set.',
         $name
      );
 
      return new static( $message, $code, $previous );
   }
}