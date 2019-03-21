<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/list", name="user_list", methods={"GET"})
     * @return Response
     */
    public function list(): Response
    {
        return $this->render('user/list.html.twig');
    }

    /**
     * @Route("/add", name="user_add", methods={"GET"})
     * @return Response
     */
    public function add(): Response
    {
        return $this->render('user/add.html.twig');
    }
}
