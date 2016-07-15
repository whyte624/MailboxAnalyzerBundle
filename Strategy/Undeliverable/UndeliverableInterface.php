<?php

namespace Whyte624\MailboxAnalyzerBundle\Strategy\Undeliverable;

use Whyte624\MailboxAnalyzerBundle\Model\Headers;

/**
 * @package Whyte624\MailboxAnalyzerBundle\Strategy\Undeliverable
 */
interface UndeliverableInterface
{
    /**
     * @param $headers
     * @param $body
     * @return mixed
     */
    public function isUndeliverable($headers, $body);

    /**
     * @param $headers
     * @param $body
     * @return mixed
     */
    public function extractReceiver($headers, $body);
}
