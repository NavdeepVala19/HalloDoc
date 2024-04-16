
<?php
/*
- The final keyword can be used with both classes and methods but act differently with both of them. 
- In classes, you must use the final keyword whenever you want the inheritance out of the way, ie, to prevent inheritance in your class.
*/

// Note: This code will throw an error as you are trying to inherit a final class.
// cannot override final method
   final class Furniture {
      final function displayMessage() {
         echo "I am a final class. You can not inherit my properties!";
      }

      function print() {
         echo "I am the Furniture class function.";
      }
   }

   class testClass extends Furniture {
      function show() {
         echo "I am the test class function.";
      }
   }

   $obj = new testClass;
   $obj->show();
?>