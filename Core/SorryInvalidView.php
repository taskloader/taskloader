<?php namespace TaskLoader\Core;

use Exception;

class SorryInvalidView extends Exception {
 


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
    public static function templateNotFound( $service, $code = 0, \Exception $previous = null ) {
        $message = sprintf(
        'File %s.php does not exist.',
        $service
        );

        return new static( $message, $code, $previous );
    }


}