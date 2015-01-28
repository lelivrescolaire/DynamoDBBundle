<?php
namespace LLS\Bundle\DynamoDBBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use LLS\Bundle\DynamoDBBundle\Session\Storage\Handler\DynamoDBSessionHandler;

/**
 * Symfony 2 Command to create Session table on DynamoDB
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class DynamoDBSessionTableCreateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('aws:dynamodb:session:table:create')
            ->setDescription('Create a table in DynamoDB to handle sessions.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sessionHandler = $this->getContainer()->get('session.handler.dynamodb');
        $options = $sessionHandler->getOptions();

        $sessionHandler->createSessionsTable();

        $output->writeln('Table "'. $options['table_name'] .'" created on DynamoDB.');
    }
}