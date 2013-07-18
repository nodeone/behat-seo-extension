<?php

namespace M6Web\Behat\SEOExtension\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * TransformerPass
 *
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 */
class TransformerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $manager = $container->getDefinition('seo_checker.seo_extension.data_transformer.manager');

        $ids = $container->findTaggedServiceIds('seo_checker.data_transformer');

        foreach ($ids as $id => $attributes) {
            $manager->addMethodCall('register', array(new Reference($id)));
        }
    }

}
