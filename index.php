<?php

// include application class
include_once(__DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'app.php');

//{{{ Instanciate and run the application
    $app = new App();
    $app->run();
//}}}