<?php
declare(strict_types=1);

namespace Console\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\ServiceManager\Factory\FactoryInterface;
use ToolkitApi\Toolkit;

final class ToolkitFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $toolkitConfig = $config['toolkit'];
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
    }
}