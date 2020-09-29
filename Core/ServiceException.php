<?php namespace TaskFiber\Core;

use \TaskFiber\FiberException;

class ServiceException extends FiberException {

   public static function invalidService( $service, $code = 0, \Exception $previous = null ) {
      $message = sprintf(
         'Service %s does not exist.',
         $service
      );
 
      return new static( $message, $code, $previous );
   }
}