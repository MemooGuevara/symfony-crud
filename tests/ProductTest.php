<?php

namespace App\Tests;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductTest extends DatabaseDependantTestCase
{
    public function testNewProduct(): void
    {
        $category = new Category();
        $category->setName('any category name');
        $category->setIsActive(true);

        $this->entityManager->persist($category);
        $this->entityManager->flush();
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $categoryRecord = $categoryRepository->findOneBy(['name' => 'any category name']);

        $product = new Product();
        $product->setCode('anycode');
        $product->setName('any name');
        $product->setBrand('any brand');
        $product->setPrice(1000);
        $product->setDescription('Any large text');
        $product->setCategory($categoryRecord);

        $this->entityManager->persist($product);
        $this->entityManager->flush();
        $productRepository = $this->entityManager->getRepository(Product::class);
        $productRecord = $productRepository->findOneBy(['code' => 'anycode']);

        $this->assertEquals('any name', $productRecord->getName());
        $this->assertEquals($categoryRecord->getName(), $productRecord->getCategory()->getName());
        $this->assertInstanceOf(\DateTime::class, $productRecord->getCreatedAt());
        $this->assertInstanceOf(\DateTime::class, $productRecord->getUpdatedAt());
    }
}
