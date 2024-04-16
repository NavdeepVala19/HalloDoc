<html>
    <body>

    <h1>the fruit program</h1>


        <?php 
         
         class Fruit {

            public $name;
            public $color;


             public function __construct($name, $color) {

                $this->name = $name;
                $this->color = $color;

             }

             public function intro(){
                echo "A {$this->name} is a fruit and color is {$this->color}";
             }

        }

        class cherry extends Fruit {

            public $weight;
            public function __construct($name,$color,$weight){

                
                $this->name = $name;
                $this->color = $color;
                $this->weight = $weight;
            }

            public function intro(){

                echo "A {$this->name} is a cherry and the color is {$this->color} weight is {$this->weight} gram";

        }
    }


    $cherry = new cherry("cherry","red","20");  
    $cherry->intro();
        ?>

    </body>
</html>