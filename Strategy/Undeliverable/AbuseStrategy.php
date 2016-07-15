<?php

namespace Whyte624\MailboxAnalyzerBundle\Strategy\Undeliverable;

/**
 * Class AbuseStrategy
 * @package Whyte624\MailboxAnalyzerBundle\Strategy\Undeliverable
 */
class AbuseStrategy implements UndeliverableInterface
{
    /**
     * {@inheritdoc}
     */
    public function isUndeliverable($headers, $body)
    {
        return strpos(strtolower($headers->subject), 'abuse report') !== false;
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