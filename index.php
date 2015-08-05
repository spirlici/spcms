<?php

// include constants
include __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'config' .  DIRECTORY_SEPARATOR . 'constants.cfg.php';

// include application class
include_once(__DIR__ . DS . 'app' . DS . 'app.php');

//{{{ Instanciate and run the application
    $app = new App();
    $app->run();
//}}}