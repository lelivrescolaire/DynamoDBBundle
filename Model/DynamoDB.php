<?php

namespace LLS\Bundle\DynamoDBBundle\Model;

use LLS\Bundle\AWSBundle\Interfaces\ClientFactoryInterface;
use LLS\Bundle\AWSBundle\Interfaces\IdentityInterface;
use LLS\Bundle\DynamoDBBundle\Interfaces\DynamoDBInterface;

use LLS\Bundle\SQSBundle\Exception\SQSQueueNotExists;

/**
 * DynamoDB Service Model
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class DynamoDB implements DynamoDBInterface
{
    /**
     * @var \Aws\Sqs\DynamoDBClient
     */
    protected $client;

    /**
     * {@inheritDoc}
     */
    public function __construct(IdentityInterface $identity, ClientFactoryInterface $clientFactory)
    {
        $this->client = $clientFactory->createClient('DynamoDB', $identity);
    }

    /**
     * {@inheritDoc}
     */
    public function getClient()
    {
        return $this->client;
    }
}