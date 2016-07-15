<?php

namespace Whyte624\MailboxAnalyzerBundle\Strategy\Undeliverable;

/**
 * Class UndeliveredStrategy
 * @package Whyte624\MailboxAnalyzerBundle\Strategy\Undeliverable
 */
class UndeliveredStrategy implements UndeliverableInterface
{
    /**
     * {@inheritdoc}
     */
    public function isUndeliverable($headers, $body)
    {
        return strpos(strtolower($headers->subject), 'undelivered mail') !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function extractReceiver($headers, $body)
    {
        if (preg_match('/^To: (.*)$/m', $body, $matches)) {
            return trim($matches[1]);
        } else {
            return '';
        }
    }
}