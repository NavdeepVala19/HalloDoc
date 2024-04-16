<?php
// 1. Public Visibility Mode: Public Visibility mode gives the least privacy to the attributes of the base class. If the visibility mode is public, it means that the derived class can access the public and protected members of the base class but not the private members of the base class. 

// Define the base class
class Furniture {
    public $price = "We have a fixed price of 50000";
    function printMessage() {
        echo $this->price;
        echo PHP_EOL;
    }
}

//  define the derived classes
class Sofa extends Furniture {
    function print(){
        echo $this->price;
    }
}

// create the object of the derived class.
$obj = new Sofa;

// call the functions
echo $obj->price;

$obj->printMessage();
$obj->print();

// 2. Protected Visibility Mode: is somewhat between the public and private modes. If the visibility mode is protected, that means the derived class can access the public and protected members of the base class protectively. 

// Define the base class
class Furniture {
    protected $price1 = 1000;
    protected $price2 = 2000;   
    // Subtraction Function
    function total(){
        echo $sum = $this->price1 + $this->price2;
    }   

}

// define the derived classes
class Sofa extends Furniture {
    function printInvoice() {
        $tax = 100;
        echo $sub = $this->price1 + $this->price2 + $tax;
    }

}

$obj= new Sofa;
$obj->total();
$obj->printInvoice();

// 3. Private Visibility Mode: gives the most privacy to the attributes of the base class. If the visibility mode is private, that means the derived class can access the public and protected members of the base class privately. 

// Define the base class
class Furniture {
    private $price = "We have a fixed price of 50000";
    private function show(){
        echo "This is private method of base class";
    }
}

//  define the derived classes
class Sofa extends Furniture {
    function printPrice(){
        echo $this->price;
    }
}

// create the object of the derived class
$obj = new Sofa;

// this line is trying to call a private method. this will throw error
$obj->show();

// this will also throw error
$obj->printPrice();
?>