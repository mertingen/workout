<?php
/**
 * Created by IntelliJ IDEA.
 * PlanDay: mert
 * Date: 3/16/19
 * Time: 1:54 PM
 */

namespace App\Controller;


use App\Entity\PlanDay;
use App\Repository\PlanDayRepository;
use App\Repository\PlanRepository;
use App\Service\FormValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PlanDayDayApiController
 * @package App\Controller
 * @Route("/api/plan-day")
 */
class PlanDayApiController extends AbstractController
{
    /**
     * @Route("/get-all", name="plan_day_get_all", methods={"GET"})
     * @param PlanDayRepository $planDayDayRepository
     * @return Response
     */
    public function getAll(PlanDayRepository $planDayDayRepository): Response
    {
        try {
            $planDayDays = $planDayDayRepository->findAll();
            return $this->json(
                array(
                    'status' => true,
                    'message' => '',
                    'data' => $planDayDays
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
     * @Route("/get/{id}", name="plan_day_get", methods={"GET"})
     * @param PlanDay $planDay
     * @return Response
     */
    public function getOne(PlanDay $planDay = NULL): Response
    {
        try {
            if (empty($planDay)) {
                return $this->json(
                    array(
                        'status' => false,
                        'message' => 'PlanDay day is not found!',
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }


            return $this->json(
                array(
                    'status' => true,
                    'message' => '',
                    'data' => $planDay
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
     * @Route("/add", name="plan_day_add_post", methods={"POST"})
     * @param Request $request
     * @param FormValidatorService $formValidatorService
     * @param EntityManagerInterface $entityManager
     * @param PlanRepository $planRepository
     * @return Response
     */
    public function add(Request $request, FormValidatorService $formValidatorService, EntityManagerInterface $entityManager, PlanRepository $planRepository): Response
    {
        try {
            $data = array();
            $data['name'] = $request->request->get('name', null);
            $data['order'] = $request->request->get('order', null);
            $data['planId'] = $request->request->get('plan', null);

            $isValid = $formValidatorService->checkPlanDayData($data);

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

            $plan = $planRepository->findOneBy(array('id' => $data['planId']));

            if (!$plan) {
                return $this->json(
                    array(
                        'status' => false,
                        'message' => 'Plan is not found.',
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }

            $planDay = new PlanDay();
            $planDay->setName($data['name']);
            $planDay->setPlanDayOrder($data['order']);
            $planDay->setPlan($plan);

            $entityManager->persist($planDay);
            $entityManager->flush();

            return $this->json(
                array(
                    'status' => $isValid['status'],
                    'message' => 'Plan day is added successfully.',
                    'data' => $planDay
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
     * @Route("/edit/{id}", name="plan_day_edit_post", methods={"POST"})
     * @param PlanDay $planDay
     * @param Request $request
     * @param FormValidatorService $formValidatorService
     * @param EntityManagerInterface $entityManager
     * @param PlanRepository $planRepository
     * @return Response
     */
    public function edit(PlanDay $planDay = NULL, Request $request, FormValidatorService $formValidatorService, EntityManagerInterface $entityManager, PlanRepository $planRepository): Response
    {
        try {

            if (empty($planDay)) {
                return $this->json(
                    array(
                        'status' => false,
                        'message' => 'Plan day is not found!',
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }

            $data = array();
            $data['name'] = $request->request->get('name', null);
            $data['order'] = $request->request->get('order', null);
            $data['planId'] = $request->request->get('plan', null);

            $isValid = $formValidatorService->checkPlanDayData($data);

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

            $plan = $planRepository->findOneBy(array('id' => $data['planId']));

            if (!$plan) {
                return $this->json(
                    array(
                        'status' => false,
                        'message' => 'Plan is not found.',
                        'data' => array()
                    ),
                    Response::HTTP_EXPECTATION_FAILED
                );
            }

            $planDay->setName($data['name']);
            $planDay->setPlanDayOrder($data['order']);
            $planDay->setPlan($plan);

            $entityManager->persist($planDay);
            $entityManager->flush();

            return $this->json(
                array(
                    'status' => $isValid['status'],
                    'message' => 'Plan day is updated successfully.',
                    'data' => $planDay
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
     * @Route("/remove/{id}", name="plan_day_remove", methods={"GET"})
     * @param PlanDay $planDay
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function remove(PlanDay $planDay = NULL, EntityManagerInterface $entityManager): Response
    {
        try {
            if (empty($planDay)) {
                return $this->json(
                    array(
                        'status' => false,
                        'message' => 'PlanDay is not found!',
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }

            $entityManager->remove($planDay);
            $entityManager->flush();

            return $this->json(
                array(
                    'status' => true,
                    'message' => 'Plan day is removed successfully.',
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