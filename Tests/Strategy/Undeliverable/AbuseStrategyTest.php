<?php

namespace Whyte624\Tests\Strategy\Undeliverable;

use Whyte624\MailboxAnalyzerBundle\Strategy\Undeliverable\AbuseStrategy;

class AbuseStrategyTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider mails
     */
    public function testIsUndeliverable($headers, $body, $result, $email = null)
    {
        $strategy = new AbuseStrategy();
        $this->assertEquals($strategy->isUndeliverable($headers, $body), $result);
        if ($result) {
            $this->assertEquals($strategy->extractReceiver($headers, $body), $email);
        }
    }

    public function mails()
    {
        return array(
            array(
                (object) array('subject' => 'New abuse report'),
                "Abuse report received\nSubject: Newsletter\nTo: client1@example.com\nFrom: newsletter@example.com",
                true,
                'client1@example.com',
            ),
            array(
                (object) array('subject' => 'Support request'),
                "Help me please",
                false,
                null,
            ),
        );
    }
}