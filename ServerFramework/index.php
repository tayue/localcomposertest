<?php

require_once '../vendor/autoload.php';
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
use ServerFramework\Di\Container;
use ServerFramework\Bean\Person;
use ServerFramework\Server\ServerManager;
//$res = new Container();
//$res['person'] = [
//    'class' => Person::class
//];
//print_r($res->get('person')->action());die("---");
//print_r($res['person']);
//print_r($res);
//$person = $res->getSingleton(Person::class);
//print_r($person);
ServerManager::getInstance()->createServer()->run();



