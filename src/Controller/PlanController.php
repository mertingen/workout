<?php

namespace App\Controller;

use App\Entity\Plan;
use App\Entity\User;
use App\Repository\UserPlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/plan")
 */
class PlanController extends AbstractController
{

    /**
     * @Route("/list", name="plan_list", methods={"GET"})
     * @return Response
     */
    public function list(): Response
    {
        return $this->render('plan/list.html.twig');
    }

    /**
     * @Route("/add", name="plan_add", methods={"GET"})
     * @return Response
     */
    public function add(): Response
    {
        return $this->render('plan/add.html.twig');
    }

    /**
     * @Route("/confirmation/{id}/{planId}", name="plan_confirmation", methods={"GET"})
     * @param User $user
     * @param Plan $plan
     * @param EntityManagerInterface $entityManager
     * @param UserPlanRepository $userPlanRepository
     * @return Response
     */
    public function confirmation(User $user = NULL, Plan $plan = NULL, EntityManagerInterface $entityManager, UserPlanRepository $userPlanRepository): Response
    {
        $data = array(
            'status' => false,
            'isAlreadyConfirmed' => false
        );
        /**
         * If user or plan are not found, front-end shows an error with these lines.
         */
        if (empty($user) || empty($plan)) {
            return $this->render('plan/confirmation.html.twig', array('data' => $data));
        }

        /**
         * If user is already has confirmed, user shows a message about that.
         */
        $userPlan = $userPlanRepository->findOneBy(array('plan' => $plan, 'user' => $user));
        if ($userPlan->getIsConfirmation()) {
            $data['isAlreadyConfirmed'] = true;
            return $this->render('plan/confirmation.html.twig', array('data' => $data));
        }

        /**
         * Confirmation is done successfully and user shows a message about that.
         */
        $userPlan->setIsConfirmation(true);

        $entityManager->persist($userPlan);
        $entityManager->flush();


        $data['status'] = true;
        return $this->render('plan/confirmation.html.twig', array('data' => $data));
    }
}
