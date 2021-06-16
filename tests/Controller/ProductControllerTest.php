<?php

namespace App\Tests\Repository;

use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/products//');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Products');
    }

    public function testNew()
    {
        $client = static::createClient();
        $client->request('GET', '/products/new');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'New Product');
    }

    public function testPDF()
    {
        $client = static::createClient();
        $client->request('GET', '/products/pdf');
        $this->assertResponseIsSuccessful();
    }
}
