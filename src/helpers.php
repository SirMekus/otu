<?php
function otuPeopleException($exception) {
    echo 'Error on line '.$exception->getLine().' in '.$exception->getFile()
    .": ".PHP_EOL. "Error: " . $exception->getMessage();
  }