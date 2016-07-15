<?php

namespace Whyte624\Tests\Strategy\Undeliverable;

use Whyte624\MailboxAnalyzerBundle\Strategy\Undeliverable\UndeliveredStrategy;

final class GroupAdminTest extends \PHPUnit_Framework_TestCase
{

    public function testIsUndeliverable()
    {
        $strategy = new UndeliveredStrategy();
        $this->assertTrue;
    }
}