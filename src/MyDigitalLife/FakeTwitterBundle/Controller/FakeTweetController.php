<?php

namespace MyDigitalLife\FakeTwitterBundle\Controller;

use MyDigitalLife\FakeTwitterBundle\Entity\Tweet;
use MyDigitalLife\FakeTwitterBundle\Entity\User;
use MyDigitalLife\FakeTwitterBundle\Form\TweetForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FakeTweetController extends Controller
{

    /**
     * @Route("/", name="homepage")
     *
     * @return Response
     */
    public function homepageAction(Request $request)
    {
        $form = $this->createForm(TweetForm::class, null, ['action' => $this->generateUrl('tweet_form')]);

        return $this->render(
            ':default:homepage.html.twig',
            [
                'tweetForm' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/timeline", name="timeline")
     */
    public function timelineAction()
    {
        $tweets = $this->get('repository.tweet')->getTimelineOfUser($this->getUser());
        return $this->render(':user:timeline.html.twig', ['tweets' => $tweets]);
    }

    /**
     * @Route("/tweet", name="tweet_form")
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createTweetAction(Request $request)
    {
        $tweet = new Tweet();
        $form = $this->createForm(TweetForm::class, $tweet, ['action' => $this->generateUrl('tweet_form')]);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $tweet->setAuthor($this->getUser());

                $this->get('service.search')->replaceUsers($tweet);
                $this->get('service.search')->replaceHashTags($tweet);


                $em = $this->get('doctrine.orm.entity_manager');
                $em->persist($tweet);
                $em->flush($tweet);

                return $this->redirectToRoute('timeline');
            }
        }

        return $this->render(':user:tweet.html.twig', ['tweetForm' => $form->createView()]);
    }

    /**
     * @Route("/timeline/{username}", name="user_timeline")
     *
     * @param string $username
     * @return Response
     */
    public function userTimelineAction(string $username)
    {
        $user = $this->get('repository.user')->findOneBy(['username' => $username]);
        $tweets = $this->get('repository.tweet')->getLatestTweetsByUser($user);

        return $this->render(':user:user_timeline.html.twig', ['user' => $user, 'tweets' => $tweets]);
    }
}