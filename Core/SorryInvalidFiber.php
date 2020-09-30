<?php namespace TaskFiber\Core;

use Exception;

class SorryInvalidFiber extends Exception {
 


   /**
    * Exception thrown if a callback is out of scope or is missing arguments.
    *
    * @param      <type>     $class     The class
    * @param      <type>     $method    The method
    * @param      string     $line      The line
    * @param      integer    $code      The code
    * @param      Exception  $previous  The previous
    *
    * @return     <type>     ( description_of_the_return_value )
    */
   public static function call
   (
      $class, $method,
      $line = 'Function %s::%s() is missing arguments or out of scope',
      $code = 0, Exception $previous = null
   ) {
 
      return new static( sprintf($line, $class, $method ), $code, $previous );
   }


   /**
    * Exception thrown if a callback refers to an undefined function.
    *
    * @param      <type>     $class     The class
    * @param      <type>     $method    The method
    * @param      string     $line      The line
    * @param      integer    $code      The code
    * @param      Exception  $previous  The previous
    *
    * @return     <type>     ( description_of_the_return_value )
    */
   public static function method
   (
      $class, $method,
      $line = 'Call to undefined method %s::%s()',
      $code = 0, Exception $previous = null
   ) {
 
      return new static( sprintf($line, $class, $method ), $code, $previous );
   }
 


   /**
    * Exception thrown if an argument is not of the expected type.
    *
    * @param      <type>     $class     The class
    * @param      <type>     $method    The method
    * @param      string     $line      The line
    * @param      integer    $code      The code
    * @param      Exception  $previous  The previous
    *
    * @return     <type>     ( description_of_the_return_value )
    */
   public static function argument
   (
      $class, $method,
      $line = 'Invalid type for argument passed to %s:%s()',
      $code = 0, Exception $previous = null
   ) {
 
      return new static( sprintf($line, $class, $method ), $code, $previous );
   }


}