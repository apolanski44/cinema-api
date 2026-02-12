<?php

namespace App\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoomControllerTest extends WebTestCase
{
    public function testGetAllRooms(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/rooms');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($responseData);
        $this->assertNotEmpty($responseData);
        
        $firstRoom = $responseData[0];
        $this->assertArrayHasKey('id', $firstRoom);
        $this->assertArrayHasKey('name', $firstRoom);
        $this->assertArrayHasKey('numberOfRows', $firstRoom);
        $this->assertArrayHasKey('seatsPerRow', $firstRoom);
        $this->assertArrayHasKey('totalSeats', $firstRoom);
        
        $this->assertIsInt($firstRoom['id']);
        $this->assertIsString($firstRoom['name']);
        $this->assertIsInt($firstRoom['numberOfRows']);
        $this->assertIsInt($firstRoom['seatsPerRow']);
        $this->assertIsInt($firstRoom['totalSeats']);
        
        $this->assertEquals(
            $firstRoom['numberOfRows'] * $firstRoom['seatsPerRow'],
            $firstRoom['totalSeats']
        );
    }
}
