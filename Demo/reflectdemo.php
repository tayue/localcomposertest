<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

require_once '../vendor/autoload.php';

use App\Dao\OrderDaoAopDemo;

OrderDaoAopDemo::class;