<?php
namespace Emmy\App\Exceptions;

use Exception;
class OtuException extends Exception 
{
  public function errorMessage() 
  {
    $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile().PHP_EOL
    .'Error: '.$this->getMessage();
    return $errorMsg;
  }
}
