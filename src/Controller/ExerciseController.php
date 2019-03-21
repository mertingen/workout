<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/exercise")
 */
class ExerciseController extends AbstractController
{

    /**
     * @Route("/list", name="exercise_list", methods={"GET"})
     * @return Response
     */
    public function list(): Response
    {
        return $this->render('exercise/list.html.twig');
    }

    /**
     * @Route("/add", name="exercise_add", methods={"GET"})
     * @return Response
     */
    public function add(): Response
    {
        return $this->render('exercise/add.html.twig');
    }
}
