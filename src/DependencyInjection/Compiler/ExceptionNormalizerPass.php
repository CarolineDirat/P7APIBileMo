<?php

namespace App\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * ExceptionNormalizerPass
 * Add normalizers to App/EventSubscriber/ExceptionSubscriber.
 */
class ExceptionNormalizerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $exceptionSubscriberDefinition = $container->findDefinition('app.exception_subscriber');
        $normalizers = $container->findTaggedServiceIds('app.normalizer');

        foreach (array_keys($normalizers) as $id) {
            $exceptionSubscriberDefinition->addMethodCall('addNormalizer', [new Reference($id)]);
        }
    }
}
