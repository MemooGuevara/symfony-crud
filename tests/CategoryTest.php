<?php

namespace App\Tests;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryTest extends DatabaseDependantTestCase
{
    public function testNewCategory(): void
    {
        $category = new Category();
        $category->setName('any category name');
        $category->setIsActive(true);

        $this->entityManager->persist($category);
        $this->entityManager->flush();
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $categoryRecord = $categoryRepository->findOneBy(['name' => 'any category name']);

        $this->assertEquals('any category name', $categoryRecord->getName());
        $this->assertEquals(true, $categoryRecord->getIsActive());
        $this->assertInstanceOf(\DateTime::class, $categoryRecord->getCreatedAt());
        $this->assertInstanceOf(\DateTime::class, $categoryRecord->getUpdatedAt());
    }
}
