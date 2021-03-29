<?php

namespace App\Controller;
use App\Entity\Department;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentController extends AbstractController
{
    /**
     * @Route("/department", name="department_index", methods={"GET"})
     * @param DepartmentRepository $departmentRepository
     * @return Response
     */
    public function index(DepartmentRepository $departmentRepository): Response
    {
        return $this->render('department/index.html.twig', [
            'departments' => $departmentRepository->findAll(),
        ]);
    }
}
