<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class DataServiceUser
{
    protected $em;
    protected $container;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
    }

    public function ReturnData($request)
    {
        $department_idStr = '';

        if (isset($request->params['department_id']) && !empty($request->params['department_id']))
            $department_id = $request->params['department_id'];

        if (!empty($department_id)) {
            $department_idStr .= " u.departmentID =" . $department_id;
        }

        $em = $this->em;
        $container = $this->container;
        $query = $em->createQuery("
                SELECT 
                u.id,
                u.email,
                u.departmentID,
                u.roles
                FROM 
                App\Entity\User u
                WHERE 
                " . $department_idStr
        );

        $pagenator = $container->get('knp_paginator');
        $results = $pagenator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return ($results);
    }
}
