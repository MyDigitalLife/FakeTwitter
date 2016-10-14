<?php

namespace MyDigitalLife\FakeTwitterBundle\Repository;

use Doctrine\ORM\EntityRepository;
use MyDigitalLife\FakeTwitterBundle\Entity\Tweet;
use MyDigitalLife\FakeTwitterBundle\Entity\User;

class TweetRepository extends EntityRepository
{
    /**
     * @param User $user
     *
     * @return array|Tweet[]
     */
    public function getLatestTweetsByUser(User $user)
    {
        return $this
            ->createQueryBuilder('t')
            ->where('t.author = :user')
            ->setParameter('user', $user->getId())
            ->setMaxResults(10)
            ->orderBy('t.created', 'DESC')
            ->addOrderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult();
    }


    public function getTimelineOfUser(User $user, $start = 0)
    {
        return $this
            ->createQueryBuilder('t')
            ->innerJoin('t.author', 'follower')
            ->innerJoin('follower.followers', 'followers')
            ->where('followers.id = :user')
            ->setParameter('user', $user->getId())
            ->setFirstResult($start)
            ->setMaxResults(10)
            ->orderBy('t.created', 'DESC')
            ->addOrderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByHashTag($searchTerm)
    {
        return $this
            ->createQueryBuilder('t')
            ->where('t.content LIKE :search')
            ->setParameter('search', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }
}