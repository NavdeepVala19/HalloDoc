<?php
// The interface lets you develop programs. You can create the interface in PHP by using the interface keyword. With the help of interfaces, you can also add public methods in your class without having to care much about the complexities and technicalities of how these methods can be implemented. 

// An interface can also be called the abstract method as the interface has only methods without the body or implementation.

// There is another important concept of concrete class in interfaces that can not be missed. Those classes that carry out interfaces are known as concrete classes. Concrete classes implement all the methods that have been defined in the interface. One thing to note here is that if you create two instances of the same name you will get an ambiguity error. 


  // syntax to define interface using the keyword "interface"

  interface Furniture {
    public function printPrice();
    public function printItem();
  }
  
  class Sofa implements Furniture {
    public function printPrice() {
      echo "Price of Sofa is: 65000" . "\n";
    }
  
    public function printItem() {
      echo "Other items are: Table and Cupboard". "\n";
    }
  }
  
  $obj = new Sofa;
  $obj->printPrice();
  $obj->printItem();
?>