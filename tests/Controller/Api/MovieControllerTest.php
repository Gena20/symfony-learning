<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieControllerTest extends WebTestCase
{
    protected KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testShowValidMovie()
    {
        $id = 700;
        $this->client->request('GET', sprintf('/api/movie/%s', $id));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testShowNotExistingMovie()
    {
        $id = 1;
        $this->client->request('GET', sprintf('/api/movie/%s', $id));

        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());
    }
}
