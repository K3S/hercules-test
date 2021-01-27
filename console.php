#!/usr/bin/env php

<?php
require_once __DIR__ . '/vendor/autoload.php';

use Console\BenchmarkCommand;
use Console\Factory\ApplicationFactory;
use Laminas\ServiceManager\ServiceManager;
use Symfony\Component\Console\Application;

$serviceManager = new ServiceManager([
    'factories' => [
        Application::class => ApplicationFactory::class,
    ],
    'invokables' => [
        BenchmarkCommand::class => BenchmarkCommand::class,
    ]
]);

/** @var Application $app */
$app = $serviceManager->get(Application::class);
$app->run();
