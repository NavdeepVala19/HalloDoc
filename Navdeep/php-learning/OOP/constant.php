
<?php  
/* 
- Constants are the identifiers that do not change their value through the course of the code. You can declare constants so that their value can not be changed by mistake. 

- The main difference between constant and variable is that the value of variables can be changed anytime, whereas you can not change a constant value once you define it.

- In PHP, constants can be created by two types:
1. define(): define() is a function that is used to create constants in the class. Define takes three parameters:
    1. name
    2. value
    3. Case-sensitivity

- define(constant_name, constant_value, case-insensitive)

- case-sensitive parameter is by default FALSE in  PHP.

2. const keyword: You can also declare a constant using the constant keyword. Unlike the define() function, you do not have to define case sensitivity, the const keyword is by default case sensitive.

*/
    // define a class  
    class example  {  
        // declare a constant variable using the const keyword  
        const constVariable = "I am a constant variable";  
    }  

// call the constant variable using the scope resolution  
echo example::constVariable;  
?>  