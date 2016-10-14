<?php

namespace MyDigitalLife\FakeTwitterBundle\Service;

use MyDigitalLife\FakeTwitterBundle\Entity\Tweet;
use MyDigitalLife\FakeTwitterBundle\Entity\User;
use MyDigitalLife\FakeTwitterBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class SearchService
{
    /** @var UserRepository */
    private $userRepository;

    /** @var Router */
    private $router;

    /**
     * SearchService constructor.
     * @param UserRepository $userRepository
     * @param Router $router
     */
    public function __construct(UserRepository $userRepository, Router $router)
    {
        $this->userRepository = $userRepository;
        $this->router = $router;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function checkHashTag(string $string) : string
    {
        if (0 !== strpos($string, '#')) {
            $string = '#' . $string;
        }

        return $string;
    }

    /**
     * @param Tweet $tweet
     *
     * @return Tweet
     */
    public function replaceUsers(Tweet $tweet) : Tweet
    {
        preg_match_all('/\@[a-z0-9_]+/i', $tweet->getContent(), $users);
        $data = array_unique($users[0]);
        foreach ($data as $match) {
            /** @var User $user */
            $user = $this->userRepository->findOneBy(['username' => substr($match, 1)]);
            if ($user !== null) {
                $url = $this->router->generate('user_timeline', ['username' => $user->getUsername()]);
                $tweet->setContent(str_replace(
                    $match,
                    $this->createUrl($url, '@' . $user->getUsername()),
                    $tweet->getContent()
                ));
            }
        };

        return $tweet;
    }

    /**
     * @param Tweet $tweet
     *
     * @return Tweet
     */
    public function replaceHashTags(Tweet $tweet) : Tweet
    {
        preg_match_all('/\#[a-z0-9_]+/i', $tweet->getContent(), $hashTags);
        foreach ($hashTags[0] as $hashTag) {
            $fullHashTag = $this->checkHashTag($hashTag);
            $url = $this->router->generate('search_hash_tag', ['search' => $fullHashTag]);
            $tweet->setContent(str_replace(
                $hashTag,
                $this->createUrl($url, $fullHashTag),
                $tweet->getContent()
            ));
        }

        return $tweet;
    }

    /**
     * @param string $url
     * @param string $text
     *
     * @return string
     */
    public function createUrl(string $url, string $text) : string
    {
        return '<a href="' . $url . '">' . $text . '</a>';
    }
}