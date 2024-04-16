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

            function __destruct()
            {
                echo"the fruit is {$this->name} and color is {$this->color}";
            }

         }


        //  this create new object and it gives value to constructor
         $apple = new Fruit("apple","red");
    
        ?>

    </body>
</html>