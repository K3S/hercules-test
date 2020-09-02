#!/usr/bin/env php

<?php
require_once __DIR__ . '/vendor/autoload.php';

use Console\BenchmarkCommand;
use Console\Factory\AdapterFactory;
use Console\Factory\ApplicationFactory;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\ServiceManager\ServiceManager;
use Symfony\Component\Console\Application;
use ToolkitApi\Toolkit;

$username = $_SERVER['HERC_USER'];
$password = $_SERVER['HERC_PWD'];

$databaseConfig = [
    'dsn' => 'odbc:DSN=*LOCAL;UID=' . $username . ';PWD=' . $password . ';DBQ=, QTEMP QGPL HERC;NAM=1;CMT=1;',
    'driver' => 'Pdo',
    'platform' => 'IbmDb2',
    'platform_options' => [
        'quote_identifiers' => true,
    ],
    'username' => $username,
    'password' => $password,
];

$toolkitConfig = [
    'XMLServiceLib' => 'QXMLSERV',
    'HelperLib' => 'QXMLSERV',
    'debug' => false,
    'trace' => false,
    'sbmjobParams' => 'QSYS/QSRVJOB/XTOOLKIT',
    'stateless' => true,
];

$appConfig = [
    'db' => $databaseConfig,
    'toolkit' => $toolkitConfig,
];

$serviceManagerConfig = [
    'factories' => [

        // Database adapter
        Adapter::class => AdapterFactory::class,

        // IBM i Toolkit
        Toolkit::class => function (ContainerInterface $container) use ($toolkitConfig) {
            /** @var Adapter $adapter */
            $adapter = $container->get(Adapter::class);

            $toolkit = new Toolkit(
                $adapter->getDriver()->getConnection()->getResource(),
                null,
                null,
                'pdo'
            );

            $toolkit->setOptions($toolkitConfig);

            return $toolkit;
        },

        Application::class => ApplicationFactory::class,
        BenchmarkCommand::class => [BenchmarkCommand::class, 'fromContainer'],
    ]
];

$serviceManager = new ServiceManager($serviceManagerConfig);
$serviceManager->setFactory('config', function (ContainerInterface $container) use ($appConfig) { return $appConfig; });
/** @var Application $app */
$app = $serviceManager->get(Application::class);
$app->run();
