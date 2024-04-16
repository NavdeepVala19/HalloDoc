
<?php
// Hierarchical Inheritance: As the name suggests, hierarchical inheritance shows a tree-like structure. There are many derived classes that are directly inherited from a base class. The following example will illustrate hierarchical inheritance in PHP. 

// base class named "Furniture"
class Furniture {
    public function Furniture() {
        echo 'This class is Furniture '; 
    } 
}

// derived class named "Sofa"
class Sofa extends Furniture {  

}

// derived class named "Cupboard"
class Cupboard extends Furniture {  

}

// creating objects of derived classes "Sofa" and "Cupboard"

$s = new Sofa();
$c = new Cupboard();
?>