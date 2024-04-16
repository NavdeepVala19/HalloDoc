
<?php
/* 
Abstract class - A class is said to be an abstract class if it contains at least one abstract method. 
Abstract methods are the methods that do not contain a body. Abstract classes totally rely on the derived class to carry out their tasks.

An abstract class is created when you only have the method name but you are not certain how to write the code for the same. 
*/

// define an abstract class

abstract class Furniture {
  public $name;
  public function __construct($name) {
    $this->name = $name;
  }

  // abstract method of the abstract class. It will be defined later in the child clases.

  abstract public function printType() : string;
}

// Derived classes are defined now
class Sofa extends Furniture {
  public function printType() : string {
    return "I am a $this->name!";
  }
}

class Table extends Furniture {
  public function printType() : string {
    return "I am a $this->name!";
  }
}

class Cupboard extends Furniture {
  public function printType() : string {
    return "I am a $this->name!";
  }
}

// Creating instances of the

// derived classes.

$Sofa = new Sofa("Sofa");
echo $Sofa->printType();

$Table = new Table("Table");
echo $Table->printType();


$Cupboard = new Cupboard("Cupboard");
echo $Cupboard->printType();
?>