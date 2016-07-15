<?php

namespace Whyte624\MailboxAnalyzerBundle\Config;

/**
 * Class Mailbox
 */
class Mailbox
{
    private $host;
    private $port;
    private $mailbox;
    private $username;
    private $password;
    private $name;

    /**
     * Mailbox constructor.
     * @param string $name
     * @param array  $data
     */
    public function __construct($name, array $data)
    {
        $this->name = $name;
        $this->host = $data['host'];
        $this->port = $data['port'];
        $this->mailbox = $data['mailbox'];
        $this->username = $data['username'];
        $this->password = $data['password'];
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return mixed
     */
    public function getMailbox()
    {
        return $this->mailbox;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHostConnectionString()
    {
        return sprintf('{%s:%s}', $this->host, $this->port);
    }

    /**
     * @return string
     */
    public function getMailboxConnectionString()
    {
        return sprintf('%s%s', $this->getHostConnectionString(), $this->mailbox);
    }
}