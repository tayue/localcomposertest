<?php
require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use App\Command\ModelCommand;
use App\Command\MessageOverTimeCommand;
use App\Command\MessageConfirmCommand;
use App\Command\MessageConfirmDelayCommand;
$application = new Application();

// 注册我们编写的命令 (commands)
$application->add(new ModelCommand());
$application->add(new MessageOverTimeCommand());
$application->add(new MessageConfirmCommand());
$application->add(new MessageConfirmDelayCommand());
$application->run();

