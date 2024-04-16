<?php
    //Global setting
    require 'Config/global.php';

//We load the controller and execute the action
if (isset($_GET["controller"])) {
    // We load the instance of the corresponding controller
    $controllerObj = controller($_GET["controller"]);
    //We launch the action
    launchAction($controllerObj);
} else {
    // We load the default controller instance
    $controllerObj = controller(CONTROLLER_DEFAULT);
    // We launch the action
    launchAction($controllerObj);
}


function controller($controller)
{

    switch ($controller) {
        case 'users':
            $strFileController = 'Controller/usersController.php';
            require_once $strFileController;
            $controllerObj = new usersController();
            break;

        default:
            $strFileController = 'Controller/usersController.php';
            require_once $strFileController;
            $controllerObj = new usersController();
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

?>