<?php

namespace App\Tests\Repository;

use App\Entity\Product;
use App\Tests\DatabaseDependantTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductRepositoryTest extends DatabaseDependantTestCase
{
    public function testGetPaginate(): void
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        $query = $productRepository->getPaginate();

        $queryTest = $this->entityManager
            ->createQuery('
                SELECT p
                FROM App:Product p
                WHERE p.code LIKE :filter OR p.name LIKE :filter OR p.brand LIKE :filter
            ')
            ->setParameter('filter', '%' . '' . '%');

        $this->assertEquals($queryTest, $query);
    }

    public function testGetPaginateWithFilter(): void
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        $filter = 'any filter';
        $query = $productRepository->getPaginate($filter);

        $queryTest = $this->entityManager
            ->createQuery('
                SELECT p
                FROM App:Product p
                WHERE p.code LIKE :filter OR p.name LIKE :filter OR p.brand LIKE :filter
            ')
            ->setParameter('filter', '%' . $filter . '%');

        $this->assertEquals($queryTest, $query);
    }
}
