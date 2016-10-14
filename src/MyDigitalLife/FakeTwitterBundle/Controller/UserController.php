<?php

namespace MyDigitalLife\FakeTwitterBundle\Controller;

use MyDigitalLife\FakeTwitterBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserController extends Controller
{
    /**
     * @Route("/follow/{username}", name="follow_user")
     *
     * @param string $username
     *
     * @return RedirectResponse
     */
    public function followUserAction($username)
    {
        /** @var User $userToFollow */
        $userToFollow = $this->get('repository.user')->findOneBy(['username' => $username]);

        /** @var User $user */
        $user = $this->getUser();
        $user->addFollowing($userToFollow);
        $this->get('doctrine.orm.entity_manager')->flush($user);

        $session = $this->get('session');
        $session->getFlashBag()->add('success', 'Your are now following ' . $userToFollow->getUsername());
        $session->save();

        return $this->redirectToRoute('timeline');
    }
}