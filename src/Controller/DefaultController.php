<?php
/**
 * Created by IntelliJ IDEA.
 * User: mert
 * Date: 3/18/19
 * Time: 7:04 PM
 */

namespace App\Controller;


use App\Entity\User;
use App\Repository\ExerciseInstanceRepository;
use App\Repository\ExerciseRepository;
use App\Repository\PlanRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller
 * @Route("/")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/dashboard", name="homepage_dashboard")
     * @param UserRepository $userRepository
     * @param PlanRepository $planRepository
     * @param ExerciseRepository $exerciseRepository
     * @param ExerciseInstanceRepository $exerciseInstanceRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(UserRepository $userRepository, PlanRepository $planRepository, ExerciseRepository $exerciseRepository, ExerciseInstanceRepository $exerciseInstanceRepository)
    {

        /**
         * To show counts and users on dashboard page, receving required values from repository.
         */
        $users = $userRepository->findAll();
        $userCount = $userRepository->count(array());
        $planCount = $planRepository->count(array());
        $exerciseCount = $exerciseRepository->count(array());
        $exerciseInstanceCount = $exerciseInstanceRepository->count(array());
        return $this->render('dashboard/index.html.twig', array(
                'users' => $users,
                'userCount' => $userCount,
                'planCount' => $planCount,
                'exerciseCount' => $exerciseCount,
                'exerciseInstanceCount' => $exerciseInstanceCount
            )
        );

    }


    /**
     * @Route("/user-detail/{id}", name="user-detail")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function userDetail(User $user = NULL)
    {
        if (empty($user)) {
            return $this->redirectToRoute('homepage_dashboard');
        }

        return $this->render('dashboard/user_detail.html.twig', array('user' => $user));

    }

}