<?php

namespace AVATOR\TurbosmsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class AVATORTurbosmsExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('avator_turbosms.login', $config['login']);
        $container->setParameter('avator_turbosms.password', $config['password']);
        $container->setParameter('avator_turbosms.sender', $config['sender']);
        $container->setParameter('avator_turbosms.debug', $config['debug']);
        $container->setParameter('avator_turbosms.save_to_db', $config['save_to_db']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
