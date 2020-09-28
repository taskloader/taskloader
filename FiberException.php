<?php namespace TaskFiber;

use Exception;

class FiberException extends Exception {
 
   public static function badFunctionCall( $class, $method, $code = 0, Exception $previous = null ) {
      $message = sprintf(
         '%s::%s is not a valid function.',
         $class, $method
      );
 
      return new \BadFunctionCallException( $message, $code, $previous );
   }
 
   public static function badMethodCall( $method, $code = 0, Exception $previous = null ) {
      $message = sprintf(
         'Method %s does not exist.',
         $method
      );
 
      return new \BadMethodCallException( $message, $code, $previous );
   }
}