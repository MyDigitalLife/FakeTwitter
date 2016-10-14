<?php

namespace MyDigitalLife\FakeTwitterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="MyDigitalLife\FakeTwitterBundle\Repository\UserRepository")
 * @ORM\Table
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="MyDigitalLife\FakeTwitterBundle\Entity\Tweet", mappedBy="author")
     */
    protected $tweets;

    /**
     * @var ArrayCollection|User[]
     *
     * @ORM\ManyToMany(targetEntity="MyDigitalLife\FakeTwitterBundle\Entity\User", mappedBy="following")
     */
    protected $followers;

    /**
     * @var ArrayCollection|User[]
     *
     * @ORM\ManyToMany(targetEntity="MyDigitalLife\FakeTwitterBundle\Entity\User", inversedBy="followers")
     */
    protected $following;

    public function __construct()
    {
        parent::__construct();
        $this->tweets = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return User
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTweets()
    {
        return $this->tweets;
    }

    /**
     * @param ArrayCollection $tweets
     *
     * @return User
     */
    public function setTweets($tweets)
    {
        $this->tweets = $tweets;
        return $this;
    }

    /**
     * @return ArrayCollection|User[]
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * @param ArrayCollection|User[] $followers
     *
     * @return User
     */
    public function setFollowers($followers)
    {
        $this->followers = $followers;
        return $this;
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function addFollower(User $user)
    {
        if(!$this->followers->contains($user)) {
            $this->followers->add($user);
        }

        return $this;
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function removeFollower(User $user)
    {
        $this->followers->removeElement($user);

        return $this;
    }

    /**
     * @return ArrayCollection|User[]
     */
    public function getFollowing()
    {
        return $this->following;
    }

    /**
     * @param ArrayCollection|User[] $following
     *
     * @return User
     */
    public function setFollowing($following)
    {
        $this->following = $following;
        return $this;
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function addFollowing(User $user)
    {
        if (!$this->following->contains($user)){
            $this->following->add($user);
        }

        return $this;
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function removeFollowing(User $user)
    {
        $this->following->removeElement($user);

        return $this;
    }
}