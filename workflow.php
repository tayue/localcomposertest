<?php
require './vendor/autoload.php';


use App\Controller\BlogController;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouteCompiler;

//$urlpath="'/blog/{slug}/book/{test}'";
//
//preg_match_all('#\{(!)?(\w+)\}#', $urlpath, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
//
//RouteCompiler::compile();
//
//print_r();
//
//print_r($matches);

//$route1 = new Route('/blog/{slug}/test/{test}', ['_controller' => BlogController::class,'_action'=>"testAction"]);
//$route = new Route('/blog/{slug}/book/{test}', ['_controller' => BlogController::class,'_action'=>"indexAction"]);
//$routes = new RouteCollection();
//$routes->add('blog_show', $route);
//$routes->add('blog_test', $route1);
//
//$context = new RequestContext('/');
//
//// Routing can match routes with incoming requests
//$matcher = new UrlMatcher($routes, $context);
//$_SERVER['REQUEST_URI']=str_ireplace("/index.php","",$_SERVER['REQUEST_URI']);
//$parameters = $matcher->match($_SERVER['REQUEST_URI']);
//var_dump($parameters);
// $parameters = [
//     '_controller' => 'App\Controller\BlogController',
//     'slug' => 'lorem-ipsum',
//     '_route' => 'blog_show'
// ]

// Routing can also generate URLs for a given route
//$generator = new UrlGenerator($routes, $context);
//$url = $generator->generate('blog_show', [
//    'slug' => 'my-blog-post',
//]);
//
//var_dump($url);



//
//$route = new Route(
//    '/archive/{month}', // path
//    ['_controller' => 'showArchive'], // default values
//    ['month' => '[0-9]{4}-[0-9]{2}'], // requirements
//    [], // options
//    '', // host
//    [], // schemes
//    [], // methods
//    '' // condition
//);
//
//// ...
//
//$parameters = $matcher->match('/archive/2012-01');
//// [
////     '_controller' => 'showArchive',
////     'month' => '2012-01',
////     'subdomain' => 'www',
////     '_route' => ...
//// ]
//
//$parameters = $matcher->match('/archive/foo');
//// throws ResourceNotFoundException
///
session_start();
echo session_id()."____\r\n";
echo session_status()."__";
session_start();
echo session_id()."@@@@@@@@@@\r\n";
echo session_status()."$$$$$$$$$";

use Symfony\Component\Workflow\DefinitionBuilder;
use Symfony\Component\Workflow\MarkingStore\MethodMarkingStore;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Workflow;

use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\SupportStrategy\InstanceOfSupportStrategy;

$definitionBuilder = new DefinitionBuilder();
$definition = $definitionBuilder->addPlaces(['draft', 'reviewed', 'rejected', 'published'])
    // Transitions are defined with a unique name, an origin place and a destination place
    ->addTransition(new Transition('to_review', 'draft', 'reviewed'))
    ->addTransition(new Transition('publish', 'reviewed', 'published'))
    ->addTransition(new Transition('reject', 'reviewed', 'rejected'))
    ->addTransition(new Transition('rejected-published', 'rejected', 'published'))
    ->build()
;

$singleState = true; // true if the subject can be in only one state at a given time
$property = 'marking'; // subject property name where the state is stored
$marking = new MethodMarkingStore($singleState, $property);
$workflow = new Workflow($definition, $marking);




$blogPostWorkflow = $workflow;
//$newsletterWorkflow = ...

$registry = new Registry();
$registry->addWorkflow($blogPostWorkflow, new InstanceOfSupportStrategy(BlogController::class));
//$registry->addWorkflow($newsletterWorkflow, new InstanceOfSupportStrategy(Newsletter::class));




$blogPost = new BlogController();
$workflow = $registry->get($blogPost);

$res=$workflow->can($blogPost, 'publish'); // False
var_dump($res);
$res=$workflow->can($blogPost, 'to_review'); // True
var_dump($res);
$res=$workflow->apply($blogPost, 'to_review'); // $blogPost is now in place "reviewed"
var_dump($res);
var_dump($blogPost);

$res=$workflow->can($blogPost, 'reject'); // True
var_dump($res);

$res=$workflow->apply($blogPost, 'reject'); // $blogPost is now in place "rejected"
var_dump($res);
var_dump($blogPost);

//$res=$workflow->can($blogPost, 'rejected-published'); // True
//var_dump($res);
//
//$res=$workflow->apply($blogPost, 'rejected-published'); // $blogPost is now in place "published"
//var_dump($res);
//var_dump($blogPost);

$res=$workflow->getEnabledTransitions($blogPost); // $blogPost can perform transition "publish" or "reject"
var_dump($res);

