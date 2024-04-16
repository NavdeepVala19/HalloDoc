<?php
   class Furniture {
      // declare member variables 
      var $name;
      var $cost;

      // define the member functions
      function Name($name){
         $this->name = $name;
      }

      function printName(){
         echo $this->name . "<br>";
      }

      function Cost($cost){
         $this->cost = $cost;
      }

      function printCost(){
         echo $this->cost . "<br>";
      }     
   }

// create objects for class "Furniture"
$Table = new Furniture();
$Sofa = new Furniture();
$Cupboard = new Furniture();

// call member functions by passing string arguments to them.
$Table->Name( "Table" );
$Sofa->Name( "Sofa" );
$Cupboard->Name( "Cupboard" );

// call functions by passing integer costs as arguments to them.
$Table->Cost( 7000 );
$Sofa->Cost( 55000 );
$Cupboard->Cost( 25000 );

// call functions to print the name of the furniture.
$Table->printName();
$Sofa->printName();
$Cupboard->printName();

// call functions to print the cost of the furniture.
$Table->printCost();
$Sofa->printCost();
$Cupboard->printCost();
?>