<?php
// Multilevel Inheritance: Multilevel Inheritance is the fourth type of inheritance that can be found in PHP. Multilevel inheritance can also be explained by a family tree. One base class exists and it inherits multiple subclasses. These subclasses (not every subclass necessarily) acts as base class and further inherits subclasses. This is just like a family having descendants over generations. 

// base class named "Furniture"
class Furniture {
    public function totalCost() {
        return  ' total furniture cost: 60000' . "<br>";
    }
}

// derived class named "Table" inherited form class "Furniture"
class Table extends Furniture {
    public function tableCost() {
        return  ' table cost: 45000' . "<br>";
    }

}

// derived class named "Study_Table" inherited form class "Table"
class Study_Table extends Table {
    public function studyTableCost() {
        return  ' study table cost: 60000' . "<br>";
    }

    public function priceList() {
        echo '1. ' .$this->totalCost();
        echo '2. ' .$this->tableCost() ;
        echo '3. ' .$this->studyTableCost() ;
    }

}

// creating object of the derived class

$obj = new Study_Table();
$obj->priceList();
?>