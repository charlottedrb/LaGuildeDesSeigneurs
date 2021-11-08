<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTest extends WebTestCase
{
    private $client; 
    private $content; 
    private static $identifier;

    public function setUp() : void
    {
        $this->client = static::createClient();
    }

    public function testCreate() 
    {
        $this->client->request('POST', '/character/create');

        $this->assertJsonResponse($this->client->getResponse());
        $this->defineIdentifier();
        $this->assertIdentifier();
    }
    
    /**
     * Tests redirect index.
     *
     * @return void
     */
    public function testRedirectIndex()
    {
        $this->client->request('GET', '/character');
        
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Tests index.
     */
    public function testIndex()
    {
        $this->client->request('GET', '/character/index');

        $this->assertJsonResponse($this->client->getResponse());
    }

    /**
     * Test d'affichage d'un caractère;
     */
    public function testDisplay()
    {
        $this->client->request('GET', '/character/display/' . self::$identifier);

        $this->assertJsonResponse();
        $this->assertIdentifier();
    }

    public function testBadIdentifier()
    {
        $this->client->request('GET', '/character/display/badIdentifier');
        $this->assertError404($this->client->getResponse()->getStatusCode());
    }

    public function testInexistingIdentifier()
    {
        $this->client->request('GET', '/character/display/7414a10767e9f5e71d2fdd262c9a34ec69543698error');
        $this->assertError404($this->client->getResponse()->getStatusCode());
    }

    public function testModify() 
    {
        $this->client->request('PUT', '/character/modify/' . self::$identifier);
        $this->assertJsonResponse();
        $this->assertIdentifier();
    }

    public function testDelete() 
    {
        $this->client->request('DELETE', '/character/delete/' . self::$identifier);
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
    }

    public function assertIdentifier()
    {
        $this->assertArrayHasKey('identifier', $this->content);
    }

    public function defineIdentifier()
    {
        self::$identifier = $this->content['identifier'];
    }

    public function assertError404($statusCode)
    {
        $this->assertEquals(404, $statusCode);
    }

    public function assertJsonResponse()
    {
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
        $this->content = json_decode($response->getContent(), true, 50);
    }
}
