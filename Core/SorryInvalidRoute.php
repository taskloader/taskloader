<?php namespace TaskLoader\Core;

class SorryInvalidRoute extends SorryInvalidTask {

   public static function name( string $route, $code = 0, \Exception $previous = null ) {
      $message = sprintf(
         'Route %s does not exist.',
         $route
      );
 
      return new static( $message, $code, $previous );
   }

   public static function requestMethod( string $method, $code = 0, \Exception $previous = null ) {
      $message = sprintf(
         'Method %s not registered.',
         $method
      );
 
      return new static( $message, $code, $previous );
   }

   public static function notFound( string $route, $code = 0, \Exception $previous = null ) {
      $message = sprintf(
         'No route %s found.',
         $route
      );
 
      return new static( $message, $code, $previous );
   }
}