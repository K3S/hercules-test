<?php
declare(strict_types=1);

namespace Console\Factory;

use Console\BenchmarkCommand;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Symfony\Component\Console\Application;

final class ApplicationFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|Application
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $application = new Application();
        $application->addCommands([
            $container->get(BenchmarkCommand::class)
        ]);

        return $application;
    }
}