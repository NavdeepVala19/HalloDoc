<?php
/* 
Static keyword - is used to directly access without creating the objects.  Methods that are recognized as static methods can be accessed directly. Static functions are only used in relation to classes rather than objects. These functions are only allowed to access the methods that are considered static methods. To achieve this feat, you need to use the static keyword.

- Those methods that are considered to be static methods are easily accessible. So, they can be shared and used by various instances of the class.
*/

// a test class to illustrate the working of the static keyword.

class test {
  // define a static function named myStaticFunction, using the "static" keyword. 
  public static function myStaticFunction() {
    echo "I am a static function.";
  }
}

// to call a static function, there is no requirement of creating an object.
// a static function can be called by using a "scope resolution" operator i.e. ::

test::myStaticFunction();

?>