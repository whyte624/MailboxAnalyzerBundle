<?php
namespace Whyte624\MailboxAnalyzerBundle\Config;

/**
 * Class Config
 */
class Config
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var Mailbox[]
     */
    protected $mailboxes = [];

    /**
     * Config constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
        if (array_key_exists('mailboxes', $config)) {
            foreach ($config['mailboxes'] as $k => $v) {
                $this->mailboxes[] = new Mailbox($k, $v);
            }
        }
    }

    /**
     * @return Mailbox[]
     */
    public function getMailboxes()
    {
        return $this->mailboxes;
    }
}