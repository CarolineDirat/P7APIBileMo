<?php

namespace App\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * ExceptionNormalizerPass
 * Add normalizers to App/EventSubscriber/ExceptionSubscriber
 * 
 * @package App\DependencyInjection\Compiler
 */
class ExceptionNormalizerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $exceptionListenerDefinition = $container->findDefinition('app.exception_subscriber');
        $normalizers = $container->findTaggedServiceIds('app.normalizer');

        foreach ($normalizers as $id => $tags) {
            $exceptionListenerDefinition->addMethodCall('addNormalizer', [new Reference($id)]);
        }
    }
}
