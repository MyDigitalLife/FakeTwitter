<?php

namespace Tests\AppBundle\Controller;

use MyDigitalLife\FakeTwitterBundle\Entity\Tweet;
use MyDigitalLife\FakeTwitterBundle\Service\SearchService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchServiceTest extends WebTestCase
{
    /** @var  SearchService */
    private $searchService;

    protected function setUp()
    {
        $this->searchService = $this->createClient()->getContainer()->get('service.search');
    }

    public function testCheckHashTag()
    {

        $outcome = $this->searchService->checkHashTag('test');
        $this->assertEquals('#test', $outcome);

        $outcome = $this->searchService->checkHashTag('#test');
        $this->assertEquals('#test', $outcome);

        $outcome = $this->searchService->checkHashTag('test123');
        $this->assertEquals('#test123', $outcome);

        $outcome = $this->searchService->checkHashTag('#test123');
        $this->assertEquals('#test123', $outcome);
    }

    public function createUrl()
    {
        $url = $this->searchService->createUrl('http://www.test.nl', 'Click here');
        $this->assertEquals('<a href="http://www.test.nl">Click here</a>', $url);
    }

    public function testReplaceUsers()
    {
        $tweet = new Tweet();
        $tweet->setContent('This is a test tweet to tester');
        $tweet = $this->searchService->replaceUsers($tweet);
        $this->assertAttributeEquals('This is a test tweet to tester', 'content', $tweet);

        $tweet = new Tweet();
        $tweet->setContent('This is a test tweet to @tester');
        $tweet = $this->searchService->replaceUsers($tweet);
        $this->assertAttributeEquals('This is a test tweet to @tester', 'content', $tweet);

        $tweet = new Tweet();
        $tweet->setContent('This is a test tweet to @spartan');
        $tweet = $this->searchService->replaceUsers($tweet);
        $this->assertAttributeEquals('This is a test tweet to <a href="/timeline/spartan">@spartan</a>', 'content', $tweet);

        $tweet = new Tweet();
        $tweet->setContent('This is a @test tweet to @spartan');
        $tweet = $this->searchService->replaceUsers($tweet);
        $this->assertAttributeEquals(
            'This is a @test tweet to <a href="/timeline/spartan">@spartan</a>',
            'content',
            $tweet
        );

        $tweet = new Tweet();
        $tweet->setContent('This is a @spartan @test tweet to @spartan');
        $tweet = $this->searchService->replaceUsers($tweet);
        $this->assertAttributeEquals(
            'This is a <a href="/timeline/spartan">@spartan</a> @test tweet to <a href="/timeline/spartan">@spartan</a>',
            'content',
            $tweet
        );

        $tweet = new Tweet();
        $tweet->setContent('This is a @spartan @teeester tweet to @spartan');
        $tweet = $this->searchService->replaceUsers($tweet);
        $this->assertAttributeEquals(
            'This is a <a href="/timeline/spartan">@spartan</a> <a href="/timeline/teeester">@teeester</a> tweet to <a href="/timeline/spartan">@spartan</a>',
            'content',
            $tweet
        );
    }

    public function testReplaceHashTags()
    {
        $tweet = new Tweet();
        $tweet->setContent('This is awesome');
        $tweet = $this->searchService->replaceHashTags($tweet);
        $this->assertAttributeEquals(
            'This is awesome',
            'content',
            $tweet
        );

        $tweet = new Tweet();
        $tweet->setContent('This is #awesome');
        $tweet = $this->searchService->replaceHashTags($tweet);
        $this->assertAttributeEquals(
            'This is <a href="/search/hashtags?search=%23awesome">#awesome</a>',
            'content',
            $tweet
        );

        $tweet = new Tweet();
        $tweet->setContent('This is #awesome #batman');
        $tweet = $this->searchService->replaceHashTags($tweet);
        $this->assertAttributeEquals(
            'This is <a href="/search/hashtags?search=%23awesome">#awesome</a> <a href="/search/hashtags?search=%23batman">#batman</a>',
            'content',
            $tweet
        );

        $tweet = new Tweet();
        $tweet->setContent('This is #awesome batman');
        $tweet = $this->searchService->replaceHashTags($tweet);
        $this->assertAttributeEquals(
            'This is <a href="/search/hashtags?search=%23awesome">#awesome</a> batman',
            'content',
            $tweet
        );
    }
}