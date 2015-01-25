<?php

namespace LLS\Bundle\DynamoDBBundle\Session\Storage\Handler;

use Aws\DynamoDb\Session\SessionHandler;

class DynamoDBSessionHandler implements \SessionHandlerInterface
{
    protected $dynamoDB;
    protected $inputOptions;
    protected $options;
    protected $sessionHandler;

    public function __construct(DynamoDB $dynamoDB, array $options = array())
    {
        $this->dynamoDB    = $dynamoDB;
        $this->inputOption = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return $this->getSessionHandler()->close();
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($session_id)
    {
        return $this->getSessionHandler()->destroy();
    }

    /**
     * {@inheritdoc}
     */
    public function gc($maxlifetime)
    {
        return $this->getSessionHandler()->gc();
    }

    /**
     * {@inheritdoc}
     */
    public function open($save_path, $name)
    {
        return $this->getSessionHandler()->open();
    }

    /**
     * {@inheritdoc}
     */
    public function read($session_id)
    {
        return $this->getSessionHandler()->read();
    }

    /**
     * {@inheritdoc}
     */
    public function write($session_id, $session_data)
    {
        return $this->getSessionHandler()->write();
    }

    public function getDynamoDB()
    {
        return $this->dynamoDB;
    }

    public function getOptions($computed = true)
    {
        if (!$computed) {
            return $this->inputOption;
        }

        if (!$this->options) {
            $this->options = array_merge(
                array(
                    'table_name'       => 'sessions',
                    'locking_strategy' => 'pessimistic',
                ),
                $this->inputOptions,
                array(
                    'dynamodb_client'  => $this->getDynamoDB()->getClient(),
                )
            );
        }

        return $this->options;
    }

    protected function getSessionHandler()
    {
        if (!$this->sessionHandler) {
            $this->sessionHandler = SessionHandler::factory($this->getOptions());
        }

        return $this->sessionHandler;
    }
}