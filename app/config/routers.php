<?php
use Phalcon\Mvc\Router;
$router = new Router();
$router->add(
    "/",
    [
        "controller" => "Index",
        "action"     => "index",
    ]
);
$router->notFound(
    [
        "controller" => "Index",
        "action"     => "NotFound",
    ]
);
//$router->handle();
return $router;