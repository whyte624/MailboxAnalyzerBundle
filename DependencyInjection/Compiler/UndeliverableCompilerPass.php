<?php

namespace Whyte624\MailboxAnalyzerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class UndeliverableCompilerPass
 * @package Whyte624\MailboxAnalyzerBundle\DependencyInjection\Compiler
 */
class UndeliverableCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('mailbox_analyzer.strategy.undeliverable_chain')) {
            return;
        }

        $definition = $container->findDefinition(
            'mailbox_analyzer.strategy.undeliverable_chain'
        );
        $taggedServices = $container->findTaggedServiceIds(
            'mailbox_analyzer.strategy.undeliverable'
        );

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall(
                'addStrategy',
                [new Reference($id)]
            );
        }
    }

}