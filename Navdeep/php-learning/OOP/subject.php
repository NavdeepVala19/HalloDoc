<!-- Class can have any number of data memebers and functions 
- class name and file name should match
- data members are declared with var keyword
-->

<?php

// define a class named subject

class subject

{

    // defining the constructor of the class

    public function __construct() {
        echo 'I am the constructor' . "<br>";
    }

    // defining a member function named field_subject with one argument named field

    public function subject_field($field) {
        echo 'This subject is specialised in ' . $field . "<br>";
    }   

}

// declare an object of the class 

// named obj1 using the "new" keyword.

// as soon as an object is created, the default constructor is automatically called.

$obj1 = new subject;        // here the default constructor of the class subject is called automatically.

// calling the function subject_field, by passing it "Mathematics" as an argument

$obj1->subject_field("Mathematics");

?>