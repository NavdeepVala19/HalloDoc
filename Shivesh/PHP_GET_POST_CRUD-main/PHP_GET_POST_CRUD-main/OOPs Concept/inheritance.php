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

            public function message(){
                echo "is cherry is fruit or not ?";
            }

        }

        
        // $cherry = new cherry("cherry" ,"red");
        $cherry ->message();
        $cherry->intro();

        ?>

    </body>
</html>