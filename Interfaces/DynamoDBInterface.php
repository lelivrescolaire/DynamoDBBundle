<?php

namespace LLS\Bundle\DynamoDBBundle\Interfaces;

use LLS\Bundle\AWSBundle\Interfaces\ServiceInterface;

/**
 * Define DynamoDB Service valid structure
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
interface DynamoDBInterface extends ServiceInterface
{
    /**
     * Get SQS Client
     *
     * @return Aws\DynamoDB\DynamoDBClient
     */
    public function getClient();
}