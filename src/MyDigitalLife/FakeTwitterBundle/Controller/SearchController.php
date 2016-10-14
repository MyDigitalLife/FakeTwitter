<?php

namespace MyDigitalLife\FakeTwitterBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    /**
     * @Route("/search/users/", name="search_user")
     */
    public function userSearchAction(Request $request)
    {
        $results = $this->get('repository.user')->findBySearchTerm($request->query->get('search'));

        return $this->render(':search:user.html.twig', ['results' => $results]);
    }

    /**
     * @Route("/search/hashtags", name="search_hash_tag")
     */
    public function hashTagSearchAction(Request $request)
    {
        $searchTerm = $this->get('service.search')->checkHashTag($request->query->get('search'));
        $results = $this->get('repository.tweet')->findByHashTag($searchTerm);

        return $this->render(':search:hashTag.html.twig', ['results' => $results]);
    }
}