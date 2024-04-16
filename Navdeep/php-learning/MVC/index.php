<?php
//Global setting
require_once 'config/global.php';

//We load the controller and execute the action
if (isset($_GET["controller"])) {
    // We load the instance of the corresponding controller
    $controllerObj = controller($_GET["controller"]);
    //We launch the action
    launchAction($controllerObj);
} else {
    // We load the default controller instance
    $controllerObj = controller(DEFAULT_CONTROLLER);
    // We launch the action
    launchAction($controllerObj);
}


function controller($controller)
{

    switch ($controller) {
        case 'user':
            require_once 'Controller/userController.php';
            $controllerObj = new userController();
            break;

        default:
            require_once "Controller/userController.php";
            $controllerObj = new userController();
            break;
    }
    return $controllerObj;
}

function launchAction($controllerObj)
{
    if (isset($_GET["action"])) {
        $controllerObj->run($_GET["action"]);
    } else {
        $controllerObj->run(DEFAULT_ACTION);
    }
}
