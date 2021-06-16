<?php

namespace App\Tests\Controller;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/categories//');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Categories');
    }

    public function testNew()
    {
        $client = static::createClient();
        $client->request('GET', '/categories/new');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'New Category');
    }

    public function testShow()
    {
        $client = static::createClient();
        $client->request('GET', '/categories/show/1');
        $this->assertResponseIsSuccessful();
    }

    public function testEdit()
    {
        $client = static::createClient();
        $client->request('GET', '/categories/edit/1');
        $this->assertResponseIsSuccessful();
    }

    public function testDelete()
    {
        $client = static::createClient();
        $client->request('GET', '/categories/delete/1');
        $this->assertResponseRedirects();
    }
}
