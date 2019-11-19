<?php
require './vendor/autoload.php';


use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use App\Controller\BlogController;

var_dump(new BlogController());
$routes = new RouteCollection();
$routes->add('blog_list', new Route('/blog', array(
    '_controller' => [BlogController::class, 'list']
)));
$routes->add('blog_show', new Route('/blog/{slug}', array(
    '_controller' => [BlogController::class, 'show']
)));
print_r($routes);
return $routes;

