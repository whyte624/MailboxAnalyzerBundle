<?php

namespace UserBundle\EventListener;

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