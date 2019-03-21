<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/exercise-instance")
 */
class ExerciseInstanceController extends AbstractController
{

    /**
     * @Route("/list", name="exercise_instance_list", methods={"GET"})
     * @return Response
     */
    public function list(): Response
    {
        return $this->render('exercise_instance/list.html.twig');
    }

    /**
     * @Route("/add", name="exercise_instance_add", methods={"GET"})
     * @return Response
     */
    public function add(): Response
    {
        return $this->render('exercise_instance/add.html.twig');
    }
}
