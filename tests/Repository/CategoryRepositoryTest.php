<?php

namespace App\Tests\Repository;

use App\Entity\Category;
use App\Tests\DatabaseDependantTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryRepositoryTest extends DatabaseDependantTestCase
{
    public function testGetPaginate(): void
    {
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $query = $categoryRepository->getPaginate();

        $queryTest = $this->entityManager
            ->createQuery('
                SELECT c
                FROM App:Category c
                WHERE c.name LIKE :filter
            ')
            ->setParameter('filter', '%' . '' . '%');

        $this->assertEquals($queryTest, $query);
    }

    public function testGetPaginateWithFilter(): void
    {
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $filter = 'any filter';
        $query = $categoryRepository->getPaginate($filter);

        $queryTest = $this->entityManager
            ->createQuery('
                SELECT c
                FROM App:Category c
                WHERE c.name LIKE :filter
            ')
            ->setParameter('filter', '%' . $filter . '%');

        $this->assertEquals($queryTest, $query);
    }
}
