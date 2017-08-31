<?php

namespace Fnx\GuzzleBundle\DependencyInjection;

use Fnx\GuzzleBundle\Command\Command;
use Fnx\GuzzleBundle\Command\Configuration as CommandConfig;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class FnxGuzzleExtension
    extends Extension
{

    const CLIENT_PREFIX = 'fnx_guzzle.client.';
    const COMMAND_PREFIX = 'fnx_guzzle.command.';
    const DEFAULT_CLIENT = self::CLIENT_PREFIX . 'default';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        if (array_key_exists('default', $config['clients'])) {
            $this->registerClient('default', $config['clients']['default'], $container);
        }

        foreach ($config['clients'] as $clientName => $clientConfig) {
            if ($clientName === 'default') {
                continue;
            }

            if (!$container->hasDefinition(self::DEFAULT_CLIENT)) {
                $this->registerClient('default', $clientConfig, $container);
            }

            $this->registerClient($clientName, $clientConfig, $container);
        }

        foreach ($config['commands'] as $commandName => $commandConfig) {
            $this->registerCommand($commandName, $commandConfig, $container);
        }
    }

    /**
     * @param string           $name
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function registerCommand(string $name, array $config, ContainerBuilder $container)
    {
        $clientName  = $config['client'];
        $clientId    = self::CLIENT_PREFIX . $clientName;
        $commandName = self::COMMAND_PREFIX . $name;

        $ccDefinition = new Definition(CommandConfig::class);
        $ccDefinition->setArguments(
            [
                new Reference($clientId),
                $config['uri'],
                $config['method'],
                $config['params'],
                $config['defaults'],
                isset($config['resultClass']) ? $config['resultClass'] : null,
                isset($config['resultType']) ? $config['resultType'] : null,
            ]
        );

        $definition = new Definition(Command::class);
        $serializer = new Reference('jms_serializer');
        $definition->setArguments(
            [
                $serializer,
                $ccDefinition,
            ]
        );

        $container->setDefinition($commandName, $definition);

        $commandManager = $container->getDefinition('fnx_guzzle.command_manager');
        $commandManager->addMethodCall(
            'add',
            [
                $name,
                $commandName,
            ]
        );
    }

    /**
     * @param string           $name
     * @param array            $clientConfig
     * @param ContainerBuilder $container
     */
    private function registerClient(string $name, array $clientConfig, ContainerBuilder $container)
    {
        $hasConfig  = array_key_exists('config', $clientConfig) && is_array($clientConfig['config']);
        $config     = $hasConfig ? $clientConfig['config'] : [];
        $params     = $config + ['base_uri' => $clientConfig['base_uri']];
        $clientName = self::CLIENT_PREFIX . $name;

        $definition = new Definition();
        $definition->setClass(Client::class);
        $definition->setArgument(0, $params);

        $container->setDefinition($clientName, $definition);
    }
}
