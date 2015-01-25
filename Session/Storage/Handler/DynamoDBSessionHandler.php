<?php

namespace LLS\Bundle\DynamoDBBundle\Session\Storage\Handler;

use Aws\DynamoDb\Session\SessionHandler;

class DynamoDBSessionHandler implements \SessionHandlerInterface
{
    const DEFAUT_TABLE_NAME = 'sessions';
    const DEFAUT_READ_PROVISIONED_THROUGHPUT  = 5;
    const DEFAUT_WRITE_PROVISIONED_THROUGHPUT = 5;

    protected $dynamoDB;
    protected $inputOptions;
    protected $options;
    protected $sessionHandler;
    protected $readProvisionedThroughput  = DynamoDBSessionHandler::DEFAUT_READ_PROVISIONED_THROUGHPUT;
    protected $writeProvisionedThroughput = DynamoDBSessionHandler::DEFAUT_WRITE_PROVISIONED_THROUGHPUT;

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

    public function createSessionsTable()
    {
        return $this->getSessionHandler()->createSessionsTable($this->readProvisionedThroughput, $this->writeProvisionedThroughput);
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
                    'table_name'       => DynamoDBSessionHandler::DEFAUT_TABLE_NAME,
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

    protected function setProvisionedThroughput($read = DynamoDBSessionHandler::DEFAUT_READ_PROVISIONED_THROUGHPUT, $write = DynamoDBSessionHandler::DEFAUT_WRITE_PROVISIONED_THROUGHPUT)
    {
        $this->readProvisionedThroughput  = $read;
        $this->writeProvisionedThroughput = $write;
    }
}