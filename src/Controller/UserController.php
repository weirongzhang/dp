<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\DepartmentType;
use App\Form\DepartType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\DataServiceUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $plainpwd = $user->getPassword();
            $encoded = $this->passwordEncoder->encodePassword($user, $plainpwd);
            $user->setPassword($encoded);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $plainpwd = $user->getPassword();
            $encoded = $this->passwordEncoder->encodePassword($user, $plainpwd);
            $user->setPassword($encoded);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/{id}/department", name="user_department", methods={"GET","POST"})
     */
    public function department(Request $request)
    {
        $form = $this->createForm(DepartmentType::class, array());
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirect($this->generateUrl('user_type', array('id' => $_POST['department']['department'])));
        }
        return $this->render('user/department.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/ajax", name="user_ajax", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function ajax(Request $request)
    {
        $form = $this->createForm(DepartType::class, array());
        $form->handleRequest($request);

        return $this->render('user/ajax.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/search", name="user_search", methods={"POST"})
     * @param $id
     * @param Request $request
     * @param DataServiceUser $query
     * @return Response
     */
    public function search($id, Request $request, DataServiceUser $query)
    {
        $request->params['department_id'] = $id;
        $data = $query->ReturnData($request);
        header('Content-Type: application/json');
        $arrayCollection = array();
        foreach($data as $k=>$item) {
            $arrayCollection[] = array(
                'id' => $item['id'],
                'email' => $item['email']
            );
        }
        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/{id}/type", name="user_type")
     * @param $id
     * @param Request $request
     * @param DataServiceUser $query
     * @return Response
     */
    public function type($id, Request $request, DataServiceUser $query)
    {
        $request->params['department_id'] = $id;

        $data = $query->ReturnData($request);
        return $this->render('user/type.html.twig', [
            'data' => $data,
            'id' => $id
        ]);
    }
}
