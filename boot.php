<?php
require './vendor/autoload.php';
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use App\Controller\BlogController;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Generator\UrlGenerator;

use Symfony\Component\Routing\RequestContext;

use Symfony\Component\Routing\RouteCompiler;

$routes = new RouteCollection();
$routes->add('blog_list', new Route('/blog', array(
    'controller' => [BlogController::class, 'list'],'action'=>'blog_list'
)));
$routes->add('blog_show', new Route('/blog/{slug}/{name}', array(
    'controller' => [BlogController::class, 'show'],'action'=>'blog_show'
)));


$context = new RequestContext('/');

// Routing can match routes with incoming requests
$matcher = new UrlMatcher($routes, $context);
$_SERVER['REQUEST_URI']=str_ireplace("/boot.php","",$_SERVER['REQUEST_URI']);
$parameters = $matcher->match($_SERVER['REQUEST_URI']);
print_r($parameters);
// $parameters = [
//     '_controller' => BlogController::class,
//     'slug' => 'lorem-ipsum',
//     '_route' => 'blog_show'
// ];

//
//$generator = new UrlGenerator($routes, $context);
//$url = $generator->generate('blog_show', [
//    'slug' => 'my-blog-post',
//]);
//
//var_dump($url);
//
//
//var_dump(new BlogController());
//
//
//print_r($routes);


