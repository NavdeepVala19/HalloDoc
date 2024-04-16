<?php
// Function overriding happens when your derived class and base class both contain a function having the same name. Both the functions should have the same number of arguments. The derived class inherits the member functions and data members from its base class. So to override a certain functionality, you need to perform function overriding. 

    // Define the base class
   class Example {
      function printMessage() {
         echo "\nExample class function declared final! <br>";
      }

      function print() {
         echo "\nI am the function of the class Example <br>";
      }
   }

   class childClass extends Example {
      function print() {
         echo "\nI am the function of the class childClass <br>";
      }
   }

   // create objects to call functions

   $obj1 = new Example;
   $obj1->print();
   $obj1->printMessage();

   $obj2 = new childClass;
   $obj2->print();
   $obj2->printMessage();

?>