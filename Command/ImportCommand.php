<?php
namespace Whyte624\MailboxAnalyzerBundle\Command;

use Whyte624\LoggingBundle\Handler\StreamHandler;
use Whyte624\MailboxAnalyzerBundle\Entity\MailboxMessage;
use Whyte624\MailboxAnalyzerBundle\Model\Headers;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;
use Whyte624\MailboxAnalyzerBundle\Strategy\Undeliverable\UndelivarableEvent;

/**
 * Class ImportCommand
 */
class ImportCommand extends ContainerAwareCommand
{
    const BATCH_SIZE = 500;

    protected function configure()
    {
        $this
            ->setName('robo-mailbox-analyzer:import')
            ->setDescription('Imports mails from mailboxes')
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
        $config = $container->get('mailbox_analyzer.config');
        $logger = $container->get('mailbox_analyzer.logger');
        if ($output->isDebug()) {
            $logger->pushHandler(new StreamHandler(STDOUT));
        }
        $logger->info('Start import');
        foreach ($config->getMailboxes() as $mboxConfig) {
            $mailbox = $mboxConfig->getName();
            
            $mbox = imap_open(
                $mboxConfig->getMailboxConnectionString(),
                $mboxConfig->getUsername(),
                $mboxConfig->getPassword()
            );
            
            if (!$mbox) {
                $logger->error(sprintf('Cannot connect to mailbox %s', $mailbox));
                continue;
            }
                
            if ($input->getOption('all')) {
                $msgnos = imap_search($mbox, 'ALL');
            } else {
                $since = new \DateTime('-1 day');
                $msgnos = imap_search($mbox, sprintf('SINCE "%s"', $since->format(\DateTime::RFC822)));
            }
            $logger->info(sprintf('Importing from %s. Number of messages: %d', $mailbox, count($msgnos)));

            if (is_array($msgnos)) {
                foreach ($msgnos as $msgno) {
                    $uid = imap_uid($mbox, $msgno);
                    /** @var Headers $headers */
                    $headers = imap_headerinfo($mbox, $msgno);
                    $body = imap_body($mbox, $msgno);
                    $this->analyze($headers, $body);
                }
            }
            imap_close($mbox);
        }
        $logger->info('Stop import');
    }

    private function analyze($headers, $body)
    {
        /** @var Headers $headers */
        $container = $this->getContainer();
        $chain = $container->get('mailbox_analyzer.strategy.undeliverable_chain');
        $em = $container->get('doctrine.orm.entity_manager');
        $dispatcher = $container->get('event_dispatcher');
        $logger = $container->get('mailbox_analyzer.logger');

        $logger->debug('Analyzing new message');

        $strategies = $chain->getStrategies();
        foreach ($strategies as $strategy) {
            if ($strategy->isUndeliverable($headers, $body)) {
                $email = $strategy->extractReceiver($headers, $body);
                $logger->info(sprintf('Undeliverable message to %s', $email));
                $event = new UndelivarableEvent($email);
                $dispatcher->dispatch(UndelivarableEvent::NAME, $event);
                break;
            }
        }
    }
}
