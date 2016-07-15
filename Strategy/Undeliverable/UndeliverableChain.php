<?php

namespace Whyte624\MailboxAnalyzerBundle\Strategy\Undeliverable;

/**
 * Class UndeliverableChain
 * @package Whyte624\MailboxAnalyzerBundle\Strategy\Undeliverable
 */
class UndeliverableChain
{
    /**
     * @var UndeliverableInterface[]
     */
    protected $strategies = [];

    /**
     * @param UndeliverableInterface $strategy
     */
    public function addStrategy(UndeliverableInterface $strategy)
    {
        $this->strategies[] = $strategy;
    }

    /**
     * @return UndeliverableInterface[]
     */
    public function getStrategies()
    {
        return $this->strategies;
    }
}