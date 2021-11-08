<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTest extends WebTestCase
{
    /**
     * Tests redirect index.
     *
     * @return void
     */
    public function testRedirectIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/character');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    /**
     * Tests index.
     */
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/character/index');

        $this->assertJsonResponse($client->getResponse());
    }

    /**
     * Test d'affichage d'un caractère;
     */
    public function testDisplay()
    {
        $client = static::createClient();
        $client->request('GET', '/character/display/7414a10767e9f5e71d2fdd262c9a34ec69543698');

        $this->assertJsonResponse($client->getResponse());
    }

    public function assertJsonResponse($response)
    {
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    }
}
