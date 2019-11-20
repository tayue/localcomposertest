<?php
require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Cmd\ModelCommand;

$application = new Application();

// 注册我们编写的命令 (commands)
$application->add(new ModelCommand());

$application->run();

