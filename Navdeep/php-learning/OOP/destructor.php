<?php
// define a class named Furniture
class Furniture {
  public $price;
  public $type;

  // constructor of the class
  function __construct($price, $type) {
    $this->price = $price;
    $this->type = $type;
  }

  // destructor of the class
  function __destruct() {
    echo "The price of this furniture is {$this->price}.\n"; 
    echo "The type of this furniture is {$this->type}.\n";
  }
}

$sofa = new Furniture("50000", "Sofa");
?>