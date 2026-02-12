<?php

namespace App\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class ReservationControllerTest extends WebTestCase
{
    public function testCreateReservation(): void
    {
        $client = static::createClient();
        
        $container = static::getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        
        $connection = $em->getConnection();
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=0');
        
        $schemaManager = $connection->createSchemaManager();
        $tables = $schemaManager->listTableNames();
        
        foreach ($tables as $table) {
            $connection->executeStatement("TRUNCATE TABLE `$table`");
        }
        
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=1');
        
        $application = new Application(self::$kernel);
        $application->setAutoExit(false);
        
        $input = new ArrayInput([
            'command' => 'doctrine:fixtures:load',
            '--no-interaction' => true,
            '--env' => 'test',
        ]);
        
        $output = new NullOutput();
        $application->run($input, $output);

        $client->request('POST', '/api/reservations', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'screeningId' => 1,
            'seats' => [
                ['row' => 1, 'seat' => 1],
                ['row' => 1, 'seat' => 2],
            ],
            'email' => 'test@example.com',
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);

        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($responseData);
        $this->assertCount(2, $responseData);
        $this->assertEquals(1, $responseData[0]['row']);
        $this->assertEquals(1, $responseData[0]['seat']);
        $this->assertEquals('test@example.com', $responseData[0]['customerEmail']);
    }
}
