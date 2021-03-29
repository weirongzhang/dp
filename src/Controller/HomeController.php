<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/home")
 */
class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home_index", methods={"GET"})
     */
    public function index(): Response
    {
        $user = $this->getUser();

        if (!empty($user)) {
            // Call whatever methods you've added to your User class
            // For example, if you added a getFirstName() method, you can use that.

            $role = $user->getRoles();

            if ($role[0] === 'ROLE_USER')
                return $this->redirect($this->generateUrl('department_index'));
            else
                return $this->redirect($this->generateUrl('user_index'));
        }
        throw new \Exception(AccessDeniedException::class);


    }
}
