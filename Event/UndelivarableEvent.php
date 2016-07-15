<?php

namespace Whyte624\MailboxAnalyzerBundle\Strategy\Undeliverable;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class UndelivarableEvent
 * @package Whyte624\MailboxAnalyzerBundle\Strategy\Undeliverable
 */
class UndelivarableEvent extends Event
{
    /**
     *
     */
    const NAME = 'mailbox_analyzer.undeliverable';

    /**
     * @var string
     */
    protected $email = '';

    /**
     * UndelivarableEvent constructor.
     * @param string $email
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}
