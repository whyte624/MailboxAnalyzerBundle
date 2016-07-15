# Configuration

## Basic configuration

The bundle allows you to define as many mailboxes as you want.

``` yaml
mailbox_analyzer:
    mailboxes:
        newsletter:
            host: mail.example.com
            port: 143
            mailbox: INBOX
            username: newsletter@example.com
            password: ***
        support:
            host: mail.example.com
            port: 143
            mailbox: INBOX
            username: support@example.com
            password: ***
         ...
```

Add analyze strategies with tag `mailbox_analyzer.strategy.undeliverable` or implement yours:

``` yaml
services:
    ...
    mailbox_analyzer.strategy.undeliverable.abuse:
        class: Whyte624\MailboxAnalyzerBundle\Strategy\Undeliverable\AbuseStrategy
        tags:
            -  { name: mailbox_analyzer.strategy.undeliverable }
    mailbox_analyzer.strategy.undeliverable.undelivered:
        class: Whyte624\MailboxAnalyzerBundle\Strategy\Undeliverable\UndeliveredStrategy
        tags:
            -  { name: mailbox_analyzer.strategy.undeliverable }
    ...
```

Then you have to catch an event with extracted email address, to unsubscribe a user.

``` php
// src/UserBundle/EventSubscriber/MailboxAnalyzerSubscriber

namespace UserBundle\EventSubscriber;

use UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Whyte624\MailboxAnalyzerBundle\Strategy\Undeliverable\UndelivarableEvent;

class MailboxAnalyzerSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [
            UndelivarableEvent::NAME => 'onUndeliverableEmail',
        ];
    }

    public function onUndeliverableEmail(UndelivarableEvent $event)
    {
        $user = $this->em->getRepository('UserBundle:User')
            ->findOneBy(['email' => $event->getEmail()]);
        if ($user instanceof User) {
            $user->setSubscribed(false);
            $this->em->persist($user);
            $this->em->flush($user);
        }
    }
}
```

And register new subscriber as service:
``` yaml
# src/UserBundle/Resources/config/services.yml
services
    ...
    user.event_listener.mailbox_analyzer_subscriber:
        class: UserBundle\EventSubscriber\MailboxAnalyzerSubscriber
        arguments: ['@doctrine.orm.entity_manager', '@robo_error']
        tags:
            - { name: kernel.event_subscriber }
     ...
```
