<?php
namespace Whyte624\MailboxAnalyzerBundle\Command;

use Whyte624\LoggingBundle\Handler\StreamHandler;
use Whyte624\MailboxAnalyzerBundle\Entity\MailboxMessage;
use Whyte624\MailboxAnalyzerBundle\Strategy\Undeliverable\UndelivarableEvent;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AnalyzeCommand
 */
class AnalyzeCommand extends ContainerAwareCommand
{
    const BATCH_SIZE = 500;

    protected function configure()
    {
        $this
            ->setName('robo-mailbox-analyzer:analyze')
            ->setDescription('Analyzes imported messages')
            ->addOption('all', null, InputOption::VALUE_NONE);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface|Output $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $chain = $container->get('mailbox_analyzer.strategy.undeliverable_chain');
        $em = $container->get('doctrine.orm.entity_manager');
        $dispatcher = $container->get('event_dispatcher');
        $logger = $container->get('mailbox_analyzer.analyze.logger');
        if ($output->isDebug()) {
            $logger->pushHandler(new StreamHandler(STDOUT));
        }

        $logger->info('Start analyze');
        $i = 0;
        $qb = $em->getRepository('RoboMailboxAnalyzerBundle:MailboxMessage')
            ->createQueryBuilder('t');
        if (!$input->getOption('all')) {
            $qb->where('t.isProcessed = :isProcessed')->setParameter('isProcessed', false);
        }
        $iterator = $qb->getQuery()->iterate();

        $strategies = $chain->getStrategies();
        while (($row = $iterator->next()) !== false) {
            $message = $row[0];
            /** @var MailboxMessage $message */
            foreach ($strategies as $strategy) {
                if ($strategy->isUndeliverable($message)) {
                    $email = $strategy->extractReceiver($message);
                    $logger->info(sprintf('Undeliverable message to %s', $email));
                    $event = new UndelivarableEvent($email);
                    $dispatcher->dispatch(UndelivarableEvent::NAME, $event);
                    break;
                }
            }
            $message->setIsProcessed(true);
            $em->persist($message);
            if (($i % self::BATCH_SIZE) === 0) {
                $em->flush();
                $em->clear();
            }
            ++$i;
        }
        $em->flush();
        
        $qb = $em->getRepository('RoboMailboxAnalyzerBundle:MailboxMessage')->createQueryBuilder('t')->delete()
            ->where('t.isProcessed = :isProcessed')
            ->setParameter('isProcessed', true);
        $qty = $qb->getQuery()->execute();
        $logger->debug(sprintf('Processed messages are deleted: %d', $qty));
        $logger->info('Stop analyze');
    }
}
