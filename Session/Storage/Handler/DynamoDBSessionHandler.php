<?php

namespace LLS\Bundle\DynamoDBBundle\Session\Storage\Handler;

use Aws\DynamoDb\Session\SessionHandler;
use LLS\Bundle\DynamoDBBundle\Model\DynamoDB;

/**
 * DynamoDB Session Handler
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
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

    /**
     * @param DynamoDB $dynamoDB DynamoDB Client
     * @param array    $options  Handler options
     */
    public function __construct(DynamoDB $dynamoDB, array $options = array())
    {
        $this->dynamoDB     = $dynamoDB;
        $this->inputOptions = $options;
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
    public function destroy($sessionId)
    {
        return $this->getSessionHandler()->destroy($sessionId);
    }

    /**
     * {@inheritdoc}
     */
    public function gc($maxlifetime)
    {
        return $this->getSessionHandler()->gc($maxlifetime);
    }

    /**
     * {@inheritdoc}
     */
    public function open($savePath, $name)
    {
        return $this->getSessionHandler()->open($savePath, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function read($sessionId)
    {
        return $this->getSessionHandler()->read($sessionId);
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $sessionData)
    {
        return $this->getSessionHandler()->write($sessionId, $sessionData);
    }

    /**
     * Create a table on DynamoDB to handle sessions
     *
     * @return boolean
     */
    public function createSessionsTable()
    {
        return $this->getSessionHandler()->createSessionsTable($this->readProvisionedThroughput, $this->writeProvisionedThroughput);
    }

    /**
     * Get DynamoDB client
     *
     * @return DynamoDB DynamoDB Client
     */
    public function getDynamoDB()
    {
        return $this->dynamoDB;
    }

    /**
     * Get handler options
     *
     * @param boolean $computed Whether comupte options or return user provided only
     *
     * @return array             Options
     */
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