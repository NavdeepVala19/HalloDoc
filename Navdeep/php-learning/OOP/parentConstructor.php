<?php

/* 
- The reason is that there might be situations when you want to use the constructor of a parent class in its child class, but to do that, you have to consider two situations that you might face while doing so.

1. When a constructor is already defined in the derived class.
- In the case when the derived class already has a constructor defined in it, calling the parent's class constructor can be made possible with the help of the scope resolution operator (i.e. the “::” operator).

*/

   class Furniture {
      public function __construct(){
         echo "I am the constructor of the parent class.\n";
      }
   }

   class Sofa extends Furniture {
      public function __construct(){
        // call the constructor of the parent class using the :: operator
         parent::__construct();
         echo "I am the constructor of the derived class.\n";
      }
   }
$obj = new Sofa();

/*
2. When the derived class does not have a constructor defined in it.
In the case when there is no constructor defined in the derived class, the constructor of its parent class will automatically be inherited by it directly. And when an instance of the derived class is created, the inherited constructor of the parent class will also be called.
*/

class Furniture{
    public function __construct(){
       echo "This is the constructor of the parent class.";
    }
 }

 class Sofa extends Furniture{

 }
 // When this object of the derived class is created, the constructor of the parent class inherited in the derived class will automatically be called.

 $obj = new Sofa();
?>