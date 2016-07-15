<?php

namespace Whyte624\MailboxAnalyzerBundle;

use Whyte624\MailboxAnalyzerBundle\DependencyInjection\Compiler\UndeliverableCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class Whyte624MailboxAnalyzerBundle
 */
class Whyte624MailboxAnalyzerBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new UndeliverableCompilerPass());
    }
}
