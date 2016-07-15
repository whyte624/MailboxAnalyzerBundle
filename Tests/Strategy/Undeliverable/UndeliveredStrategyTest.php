<?php

namespace Whyte624\Tests\Strategy\Undeliverable;

use Whyte624\MailboxAnalyzerBundle\Strategy\Undeliverable\UndeliveredStrategy;

class UndeliveredStrategyTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider mails
     */
    public function testIsUndeliverable($headers, $body, $result, $email = null)
    {
        $strategy = new UndeliveredStrategy();
        $this->assertEquals($strategy->isUndeliverable($headers, $body), $result);
        if ($result) {
            $this->assertEquals($strategy->extractReceiver($headers, $body), $email);
        }
    }

    public function mails()
    {
        return array(
            array(
                (object) array('subject' => 'New undelivered mail'),
                "Email undelivered\nSubject: Newsletter\nTo: someemail@example.com\nFrom: newsletter@example.com",
                true,
                'someemail@example.com',
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