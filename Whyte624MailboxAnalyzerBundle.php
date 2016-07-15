<?php

namespace Whyte624\MailboxAnalyzerBundle;

use Whyte624\MailboxAnalyzerBundle\DependencyInjection\Compiler\UndeliverableCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class RoboMailboxAnalyzerBundle
 */
class RoboMailboxAnalyzerBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new UndeliverableCompilerPass());
    }
}
