<?php
/**
 * Created by IntelliJ IDEA.
 * Plan: mert
 * Date: 3/16/19
 * Time: 1:54 PM
 */

namespace App\Controller;


use App\Entity\Plan;
use App\Entity\UserPlan;
use App\Repository\PlanRepository;
use App\Repository\UserRepository;
use App\Service\FormValidatorService;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PlanApiController
 * @package App\Controller
 * @Route("/api/plan")
 */
class PlanApiController extends AbstractController
{
    /**
     * @Route("/get-all", name="plan_get_all", methods={"GET"})
     * @param PlanRepository $planRepository
     * @return Response
     */
    public function getAll(PlanRepository $planRepository): Response
    {
        try {
            $plans = $planRepository->findAll();
            return $this->json(
                array(
                    'status' => true,
                    'message' => '',
                    'data' => $plans
                ),
                Response::HTTP_OK
            );

        } catch (\Exception $e) {
            return $this->json(
                array(
                    'status' => false,
                    'message' => $e->getMessage(),
                    'data' => array()
                ),
                Response::HTTP_EXPECTATION_FAILED
            );
        }
    }

    /**
     * @Route("/get/{id}", name="plan_get", methods={"GET"})
     * @param Plan $plan
     * @return Response
     */
    public function getOne(Plan $plan = NULL): Response
    {
        try {
            if (empty($plan)) {
                return $this->json(
                    array(
                        'status' => false,
                        'message' => 'Plan is not found!',
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }

            $planData = array(
                'id' => $plan->getId(),
                'name' => $plan->getName(),
                'description' => $plan->getDescription(),
                'difficulty' => $plan->getDifficulty()
            );

            foreach ($plan->getUserPlans() as $userPlan) {
                $planData['users'][] = $userPlan->getUser();
            }


            return $this->json(
                array(
                    'status' => true,
                    'message' => '',
                    'data' => $planData
                ),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->json(
                array(
                    'status' => false,
                    'message' => $e->getMessage(),
                    'data' => array()
                ),
                Response::HTTP_EXPECTATION_FAILED
            );
        }
    }


    /**
     * @Route("/add", name="plan_add_post", methods={"POST"})
     * @param Request $request
     * @param FormValidatorService $formValidatorService
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param NotificationService $notificationService
     * @return Response
     */
    public function add(Request $request, FormValidatorService $formValidatorService, EntityManagerInterface $entityManager, UserRepository $userRepository, NotificationService $notificationService): Response
    {
        try {
            $data = array();
            $data['name'] = $request->request->get('name', null);
            $data['description'] = $request->request->get('description', null);
            $data['difficulty'] = $request->request->get('difficulty', null);
            $data['userIds'] = $request->request->get('users', null);

            $isValid = $formValidatorService->checkPlanData($data);

            if (!$isValid['status']) {
                return $this->json(
                    array(
                        'status' => $isValid['status'],
                        'message' => $isValid['message'],
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }

            $plan = new Plan();
            $plan->setName($data['name']);
            $plan->setDescription($data['description']);
            $plan->setDifficulty($data['difficulty']);

            /**
             * Related users are fetching from DB as an object.
             * After that, to create relationship with Plan, we've to insert as UserPlan
             * For this, creating a UserPlan object.
             */
            $users = array();
            foreach ($data['userIds'] as $userId) {
                $user = $userRepository->findOneBy(array('id' => $userId));
                $userPlan = new UserPlan();
                $userPlan->setPlan($plan);
                $userPlan->setUser($user);
                $userPlan->setIsConfirmation(false);
                $entityManager->persist($userPlan);

                $users[] = $user;
            }

            $entityManager->persist($plan);
            $entityManager->flush();

            foreach ($users as $user) {
                $notificationData = array(
                    'to' => $user->getEmail(),
                    'from' => $this->getParameter('notification_sender'),
                    'body' => $notificationService->getPlanConfirmationBody($user, $plan, $this->getParameter('notification_confirm_url')),
                    'subject' => $this->getParameter('notification_confirm_subject')
                );

                $notificationService->send($notificationData);
            }

            return $this->json(
                array(
                    'status' => $isValid['status'],
                    'message' => 'Plan is added successfully.',
                    'data' => $plan
                ),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->json(
                array(
                    'status' => false,
                    'message' => $e->getMessage(),
                    'data' => array()
                ),
                Response::HTTP_EXPECTATION_FAILED
            );
        }
    }

    /**
     * @Route("/edit/{id}", name="plan_edit_post", methods={"POST"})
     * @param Plan $plan
     * @param Request $request
     * @param FormValidatorService $formValidatorService
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param NotificationService $notificationService
     * @return Response
     */
    public function edit(Plan $plan = NULL, Request $request, FormValidatorService $formValidatorService, EntityManagerInterface $entityManager, UserRepository $userRepository, NotificationService $notificationService): Response
    {
        try {

            if (empty($plan)) {
                return $this->json(
                    array(
                        'status' => false,
                        'message' => 'Plan is not found!',
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }

            $data = array();
            $data['name'] = $request->request->get('name', null);
            $data['description'] = $request->request->get('description', null);
            $data['difficulty'] = $request->request->get('difficulty', null);
            $data['userIds'] = $request->request->get('users', null);

            $isValid = $formValidatorService->checkPlanData($data);

            if (!$isValid['status']) {
                return $this->json(
                    array(
                        'status' => $isValid['status'],
                        'message' => $isValid['message'],
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }

            $plan->setName($data['name']);
            $plan->setDescription($data['description']);
            $plan->setDifficulty($data['difficulty']);

            $planUsers = $plan->getUserPlans();
            $planUsersIds = array();
            /**
             * If there are related planUsers which belong the Plan
             * They are controlled to remove.
             */
            if (count($planUsers) > 0) {
                foreach ($planUsers as $planUser) {
                    if (!in_array($planUser->getUser()->getId(), $data['userIds'])) {
                        $notificationData = array(
                            'to' => $planUser->getUser()->getEmail(),
                            'from' => $this->getParameter('notification_sender'),
                            'body' => $notificationService->getPlanRemovedBody($planUser->getUser(), $plan),
                            'subject' => $this->getParameter('notification_removed_subject')
                        );
                        $notificationService->send($notificationData);

                        $entityManager->remove($planUser);
                    } else {
                        $planUsersIds[] = $planUser->getUser()->getId();
                    }
                }
            }
            /**
             * After removing process, this place is defining to update or create.
             */
            foreach ($data['userIds'] as $userId) {
                $user = $userRepository->findOneBy(array('id' => $userId));
                /**
                 * If there is no any user, this is going 'else' field.
                 */
                if (count($planUsersIds) > 0) {
                    if (!in_array($userId, $planUsersIds)) {
                        $newUserPlan = new UserPlan();
                        $newUserPlan->setUser($user);
                        $newUserPlan->setPlan($plan);
                        $newUserPlan->setIsConfirmation(false);
                        $entityManager->persist($newUserPlan);

                        $notificationData = array(
                            'to' => $user->getEmail(),
                            'from' => $this->getParameter('notification_sender'),
                            'body' => $notificationService->getPlanConfirmationBody($user, $plan, $this->getParameter('notification_confirm_url')),
                            'subject' => $this->getParameter('notification_confirm_subject')
                        );
                        $notificationService->send($notificationData);
                    } else {
                        $notificationData = array(
                            'to' => $user->getEmail(),
                            'from' => $this->getParameter('notification_sender'),
                            'body' => $notificationService->getPlanEditedBody($user, $plan),
                            'subject' => $this->getParameter('notification_edited_subject')
                        );
                        $notificationService->send($notificationData);
                    }
                } else {
                    $newUserPlan = new UserPlan();
                    $newUserPlan->setUser($user);
                    $newUserPlan->setPlan($plan);
                    $newUserPlan->setIsConfirmation(false);
                    $entityManager->persist($newUserPlan);

                    $notificationData = array(
                        'to' => $user->getEmail(),
                        'from' => $this->getParameter('notification_sender'),
                        'body' => $notificationService->getPlanConfirmationBody($user, $plan, $this->getParameter('notification_confirm_url')),
                        'subject' => $this->getParameter('notification_confirm_subject')
                    );
                    $notificationService->send($notificationData);
                }
            }

            $entityManager->persist($plan);
            $entityManager->flush();

            return $this->json(
                array(
                    'status' => $isValid['status'],
                    'message' => 'Plan is updated successfully.',
                    'data' => $plan
                ),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->json(
                array(
                    'status' => false,
                    'message' => $e->getMessage(),
                    'data' => array()
                ),
                Response::HTTP_EXPECTATION_FAILED
            );
        }
    }

    /**
     * @Route("/remove/{id}", name="plan_remove", methods={"GET"})
     * @param Plan $plan
     * @param EntityManagerInterface $entityManager
     * @param NotificationService $notificationService
     * @return Response
     */
    public function remove(Plan $plan = NULL, EntityManagerInterface $entityManager, NotificationService $notificationService): Response
    {
        try {
            if (empty($plan)) {
                return $this->json(
                    array(
                        'status' => false,
                        'message' => 'Plan is not found!',
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }

            /**
             * Due to removing, user will have been informed by an e-mail.
             */
            $userPlans = $plan->getUserPlans();
            foreach ($userPlans as $userPlan) {
                $notificationData = array(
                    'to' => $userPlan->getUser()->getEmail(),
                    'from' => $this->getParameter('notification_sender'),
                    'body' => $notificationService->getPlanRemovedBody($userPlan->getUser(), $plan),
                    'subject' => $this->getParameter('notification_confirm_subject')
                );
                $notificationService->send($notificationData);
            }

            $entityManager->remove($plan);
            $entityManager->flush();

            return $this->json(
                array(
                    'status' => true,
                    'message' => 'Plan is removed successfully.',
                    'data' => array()
                ),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->json(
                array(
                    'status' => false,
                    'message' => $e->getMessage(),
                    'data' => array()
                ),
                Response::HTTP_EXPECTATION_FAILED
            );
        }
    }

}