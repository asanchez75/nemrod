<?php

/*
 * This file is part of the Nemrod package.
 *
 * (c) Conjecto <contact@conjecto.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Conjecto\Nemrod\Bundle\ElasticaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

/**
 * FrameworkExtension.
 */
class ElasticaExtension extends Extension
{
    /**
     * Responds to the app.config configuration parameter.
     *
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @throws LogicException
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        //register elastica indexes and mappings
        $this->registerElasticaIndexes($config, $container);

        // register jsonld frames paths
        $this->registerJsonLdFramePaths($config, $container);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    public function registerElasticaIndexes(array $config, ContainerBuilder $container)
    {

        $confManager = $container->getDefinition('nemrod.elastica.config_manager');

        foreach ($config['indexes'] as $name => $types) {
            $clientRef = new Reference('nemrod.elastica.client.' . $types['client']);
            $container
                ->setDefinition('nemrod.elastica.index.' . $name, new DefinitionDecorator('nemrod.elastica.index'))
                ->setArguments(array($clientRef, $name))
                ->addTag('nemrod.elastica.name', array('name' => $name));

            if (isset($types['settings']['index'])) {
                $confManager->addMethodCall('setIndexConfig', array($name, $types['settings']));
            }

            $indexRegistry = $container->getDefinition('nemrod.elastica.index_registry');
            $indexRegistry->addMethodCall('registerIndex', array($name, new Reference('nemrod.elastica.index.' . $name)));

        }

        foreach ($config['clients'] as $name => $client) {
            $container
                ->setDefinition('nemrod.elastica.client.'.$name, new DefinitionDecorator('nemrod.elastica.client'))
                ->setArguments(array(array(
                    'host' => $client['host'],
                    'port' => $client['port'],
                )));
        }

        $serializerHelper = $container->getDefinition('nemrod.elastica.serializer_helper');
        $serializerHelper->addMethodCall('setConstructedGraphProvider', array(new Reference('nemrod.jsonld.graph_provider')));
        $serializerHelper->addMethodCall('setJsonLdFrameLoader', array(new Reference('nemrod.elastica.jsonld.frame.loader.filesystem')));
        $serializerHelper->addMethodCall('setConfig', array($config));
    }

    /**
     * Register jsonld frames paths for each bundle.
     *
     * @return string
     */
    public function registerJsonLdFramePaths($config, ContainerBuilder $container)
    {
        $jsonLdFilesystemLoaderDefinition = $container->getDefinition('nemrod.elastica.jsonld.frame.loader.filesystem');
        foreach ($container->getParameter('kernel.bundles') as $bundle => $class) {
            // in app
            if (is_dir($dir = $container->getParameter('kernel.root_dir').'/Resources/'.$bundle.'/frames')) {
                $this->addJsonLdFramePath($jsonLdFilesystemLoaderDefinition, $dir, $bundle);
            }

            // in bundle
            $reflection = new \ReflectionClass($class);
            if (is_dir($dir = dirname($reflection->getFilename()).'/Resources/frames')) {
                $this->addJsonLdFramePath($jsonLdFilesystemLoaderDefinition, $dir, $bundle);
            }
        }

        if (is_dir($dir = $container->getParameter('kernel.root_dir').'/Resources/frames')) {
            $jsonLdFilesystemLoaderDefinition->addMethodCall('addPath', array($dir));
        }
    }

    /**
     * Add a jsonld frame path.
     *
     * @param $jsonLdFilesystemLoaderDefinition
     * @param $dir
     * @param $bundle
     */
    private function addJsonLdFramePath($jsonLdFilesystemLoaderDefinition, $dir, $bundle)
    {
        $name = $bundle;
        if ('Bundle' === substr($name, -6)) {
            $name = substr($name, 0, -6);
        }
        $jsonLdFilesystemLoaderDefinition->addMethodCall('addPath', array($dir, $name));
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return 'elastica';
    }
}
