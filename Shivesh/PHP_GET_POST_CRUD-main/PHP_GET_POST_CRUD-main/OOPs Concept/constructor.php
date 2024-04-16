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

            function get_name()
            {

                return $this->name;

            }

            function get_color()
            {

                return $this->color;

            }


         }


        //  this create new object and it gives value to constructor
         $apple = new Fruit("apple","red");
         echo   $apple->get_name();
         echo "<br>";
         echo $apple->get_color();
        ?>

    </body>
</html>