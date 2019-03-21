<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/plan-day")
 */
class PlanDayController extends AbstractController
{

    /**
     * @Route("/list", name="plan_day_list", methods={"GET"})
     * @return Response
     */
    public function list(): Response
    {
        return $this->render('plan_day/list.html.twig');
    }

    /**
     * @Route("/add", name="plan_day_add", methods={"GET"})
     * @return Response
     */
    public function add(): Response
    {
        return $this->render('plan_day/add.html.twig');
    }
}
