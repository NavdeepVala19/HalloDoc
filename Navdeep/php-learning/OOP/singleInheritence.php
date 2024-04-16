<?php
// Single Inheritance: Single inheritance is the most basic type of inheritance. In single inheritance, there is only one base class and one sub or derived class. The subclass is directly inherited from the base class.The following example will illustrate single inheritance in PHP. 

// base class named "Furniture"
class Furniture {
    var $cost = 1000;
    public function printName($name) {
        echo 'Class is: Furniture & name of furniture is: ' . $name . "<br>"; 
    } 
}

// derived class named "Sofa"
class Sofa extends Furniture {
    public function printName($name) {
        echo 'Class is: Sofa & name of furniture is: ' . $name . "<br>";
        // this class can access data member of its parent class.
        echo 'Price is: ' . $this->cost ;
    }

}

$f = new Furniture();
$s = new Sofa();
$f->printName('Table'); 
$s->printName('Sofa'); 
?>