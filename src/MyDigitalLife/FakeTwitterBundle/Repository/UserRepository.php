<?php

namespace MyDigitalLife\FakeTwitterBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findBySearchTerm($searchTerm)
    {
        return $this
            ->createQueryBuilder('u')
            ->where('u.username LIKE :user')
            ->setParameter('user', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }
}