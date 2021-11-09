<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlayerControllerTest extends WebTestCase
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
        $this->client->request(
            'POST', 
            '/player/create', 
            array(), //parameters
            array(), //files
            array('CONTENT_TYPE' => 'application/json'), //server   
            '{"firstname":"Thomas","lastname":"Ghignon","email": "thomas.ghignon@gmail.com","mirian":999, "pseudo":"Starf" }'
        );

        $this->assertJsonResponse();
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
        $this->client->request('GET', '/player');
        
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Tests index.
     */
    public function testIndex()
    {
        $this->client->request('GET', '/player/index');

        $this->assertJsonResponse();
    }

    /**
     * Test d'affichage d'un caractère;
     */
    public function testDisplay()
    {
        $this->client->request('GET', '/player/display/' . self::$identifier);

        $this->assertJsonResponse();
        $this->assertIdentifier();
    }

    /**
     * Test d'un mauvais identifier;
     */
    public function testBadIdentifier()
    {
        $this->client->request('GET', '/player/display/badIdentifier');
        $this->assertError404($this->client->getResponse()->getStatusCode());
    }

    /**
     * Test d'un identifier non existant;
     */
    public function testInexistingIdentifier()
    {
        $this->client->request('GET', '/player/display/7ba48618db8e2108a57604108ff9bc835a3error');
        $this->assertError404($this->client->getResponse()->getStatusCode());
    }

    /**
     * Test d'une modification de player;
     */
    public function testModify() 
    {
        // Test partiel
        $this->client->request(
            'PUT', 
            '/player/modify/' . self::$identifier,
            array(), 
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"firstname": "Charlotte", "lastname": "Der Baghdassarian"}'
        );

        $this->assertJsonResponse();
        $this->assertIdentifier();

        // Test complet
        $this->client->request(
            'PUT', 
            '/player/modify/' . self::$identifier,
            array(), //parameters
            array(), //files
            array('CONTENT_TYPE' => 'application/json'), //server   
            '{"firstname":"Charlotte","lastname":"Der Baghdassarian","email": "charlotte.db@gmail.com","mirian":10000000, "pseudo":"Chachou" }'
        );

        $this->assertJsonResponse();
        $this->assertIdentifier();
    }

    /**
     * Test d'une suppression de player;
     */
    public function testDelete() 
    {
        $this->client->request('DELETE', '/player/delete/' . self::$identifier);
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test d'assertion d'un identifier
     */
    public function assertIdentifier()
    {
        $this->assertArrayHasKey('identifier', $this->content);
    }

    /**
     * Définition de l'identifier
     */
    public function defineIdentifier()
    {
        self::$identifier = $this->content['identifier'];
    }

    /**
     * Test d'assertion d'une erreur 404
     */
    public function assertError404($statusCode)
    {
        $this->assertEquals(404, $statusCode);
    }

    /**
     * Test d'assertion d'une réponse JSON
     */
    public function assertJsonResponse()
    {
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
        $this->content = json_decode($response->getContent(), true, 50);
    }
}
