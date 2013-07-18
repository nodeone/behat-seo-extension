<?php

namespace SEOChecker\Behat\SEOExtension;

use Behat\Behat\Extension\ExtensionInterface;
use SEOChecker\Behat\SEOExtension\DependencyInjection\Compiler\TransformerPass;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * Extension
 *
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */
class Extension implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/DependencyInjection/services'));
        $loader->load('roboxt.xml');
        $loader->load('core.xml');

        $robotInitializer = $container->getDefinition('roboxt.file');
        $robotInitializer->replaceArgument(0, $config['robots_file']);

        $outlineSubscriber = $container->getDefinition('seo_checker.seo_extension.event_subscriber.outline');
        $outlineSubscriber->replaceArgument(0, $config['rules_file']);

        if ($enabled = $config['disable_listener']) {
            $outlineSubscriber->addMethodCall('disable');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->scalarNode('disable_listener')->defaultFalse()->end()
                ->scalarNode('robots_file')
                    ->info('The robots.txt file that is used to check the content of the website\'s file.')
                    ->defaultValue('%behat.paths.base%/data/robots.txt')
                ->end()
                ->scalarNode('rules_file')
                    ->info('The file that containsthe rules for each urls.')
                    ->defaultValue('%behat.paths.base%/data/rules.csv')
                ->end()
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    public function getCompilerPasses()
    {
        return [new TransformerPass()];
    }

}
