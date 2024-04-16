<?php

// Function overloading takes place when you create two functions of the same name and these functions serve different purposes. Both functions should have strictly different arguments from each other. 

class Example {
    public function __call($argument1, $argument2){  
        echo "I am being called using the object method '$argument1' " . implode(', ', $argument2). "<br>";
    }

    public static function __callStatic($argument1, $argument2) { 
        echo "I am being called using the object method '$argument1' ". implode(', ', $argument2). "<br>";
    }
}

$obj = new Example;
$obj->overloadingExample('with the context of object');
Example::overloadingExample('with the context of static');
?>