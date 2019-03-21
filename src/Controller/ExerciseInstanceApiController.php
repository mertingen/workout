<?php
/**
 * Created by IntelliJ IDEA.
 * Exercise: mert
 * Date: 3/16/19
 * Time: 1:54 PM
 */

namespace App\Controller;


use App\Entity\Exercise;
use App\Entity\ExerciseInstance;
use App\Repository\ExerciseInstanceRepository;
use App\Repository\ExerciseRepository;
use App\Repository\PlanDayRepository;
use App\Service\FormValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ExerciseInstanceApiController
 * @package App\Controller
 * @Route("/api/exercise-instance")
 */
class ExerciseInstanceApiController extends AbstractController
{
    /**
     * @Route("/get-all", name="exercise_instance_get_all", methods={"GET"})
     * @param ExerciseInstanceRepository $exerciseInstanceRepository
     * @return Response
     */
    public function getAll(ExerciseInstanceRepository $exerciseInstanceRepository): Response
    {
        try {
            $exerciseInstances = $exerciseInstanceRepository->findAll();
            return $this->json(
                array(
                    'status' => true,
                    'message' => '',
                    'data' => $exerciseInstances
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
     * @Route("/get/{id}", name="exercise_instance_get", methods={"GET"})
     * @param ExerciseInstance|null $exerciseInstance
     * @return Response
     */
    public function getOne(ExerciseInstance $exerciseInstance = NULL): Response
    {
        try {
            if (empty($exerciseInstance)) {
                return $this->json(
                    array(
                        'status' => false,
                        'message' => 'Exercise instance is not found!',
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }


            return $this->json(
                array(
                    'status' => true,
                    'message' => '',
                    'data' => $exerciseInstance
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
     * @Route("/add", name="exercise_instance_add_post", methods={"POST"})
     * @param Request $request
     * @param FormValidatorService $formValidatorService
     * @param EntityManagerInterface $entityManager
     * @param ExerciseRepository $exerciseRepository
     * @param PlanDayRepository $planDayRepository
     * @return Response
     */
    public function add(Request $request, FormValidatorService $formValidatorService, EntityManagerInterface $entityManager, ExerciseRepository $exerciseRepository, PlanDayRepository $planDayRepository): Response
    {
        try {
            $data = array();
            $data['exerciseId'] = $request->request->get('exercise', null);
            $data['planDayId'] = $request->request->get('plan-day', null);
            $data['duration'] = $request->request->get('duration', null);
            $data['order'] = $request->request->get('order', null);

            $isValid = $formValidatorService->checkExerciseInstanceData($data);

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

            $exercise = $exerciseRepository->findOneBy(array('id' => $data['exerciseId']));
            if (!$exercise) {
                return $this->json(
                    array(
                        'status' => false,
                        'message' => 'Exercise is not found.',
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }

            $planDay = $planDayRepository->findOneBy(array('id' => $data['planDayId']));
            if (!$planDay) {
                return $this->json(
                    array(
                        'status' => false,
                        'message' => 'Plan day is not found.',
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }

            $exerciseInstance = new ExerciseInstance();
            $exerciseInstance->setDuration($data['duration']);
            $exerciseInstance->setInstanceOrder($data['order']);
            $exerciseInstance->setExercise($exercise);
            $exerciseInstance->setPlanDay($planDay);

            $entityManager->persist($exerciseInstance);
            $entityManager->flush();

            return $this->json(
                array(
                    'status' => $isValid['status'],
                    'message' => 'Exercise instance is added successfully.',
                    'data' => $exercise
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
     * @Route("/edit/{id}", name="exercise_instance_edit_post", methods={"POST"})
     * @param ExerciseInstance|null $exerciseInstance
     * @param Request $request
     * @param FormValidatorService $formValidatorService
     * @param EntityManagerInterface $entityManager
     * @param ExerciseRepository $exerciseRepository
     * @param PlanDayRepository $planDayRepository
     * @return Response
     */
    public function edit(ExerciseInstance $exerciseInstance = NULL, Request $request, FormValidatorService $formValidatorService, EntityManagerInterface $entityManager, ExerciseRepository $exerciseRepository, PlanDayRepository $planDayRepository): Response
    {
        try {

            if (empty($exerciseInstance)) {
                return $this->json(
                    array(
                        'status' => false,
                        'message' => 'Exercise is not found!',
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }

            $data = array();
            $data['exerciseId'] = $request->request->get('exercise', null);
            $data['planDayId'] = $request->request->get('plan-day', null);
            $data['duration'] = $request->request->get('duration', null);
            $data['order'] = $request->request->get('order', null);


            $isValid = $formValidatorService->checkExerciseInstanceData($data);

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

            /**
             * To set related Exercise, we've to fetch firstly as an object with this way.
             */
            $exercise = $exerciseRepository->findOneBy(array('id' => $data['exerciseId']));
            if (!$exercise) {
                return $this->json(
                    array(
                        'status' => false,
                        'message' => 'Exercise is not found.',
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }

            /**
             * To set related Plan Day, we've to fetch firstly as an object with this way.
             */
            $planDay = $planDayRepository->findOneBy(array('id' => $data['planDayId']));
            if (!$planDay) {
                return $this->json(
                    array(
                        'status' => false,
                        'message' => 'Plan day is not found.',
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }

            $exerciseInstance->setDuration($data['duration']);
            $exerciseInstance->setInstanceOrder($data['order']);
            $exerciseInstance->setExercise($exercise);
            $exerciseInstance->setPlanDay($planDay);

            $entityManager->persist($exercise);
            $entityManager->flush();

            return $this->json(
                array(
                    'status' => $isValid['status'],
                    'message' => 'Exercise instance is updated successfully.',
                    'data' => $exerciseInstance
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
     * @Route("/remove/{id}", name="exercise_remove", methods={"GET"})
     * @param ExerciseInstance|null $exerciseInstance
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function remove(ExerciseInstance $exerciseInstance = NULL, EntityManagerInterface $entityManager): Response
    {
        try {
            if (empty($exerciseInstance)) {
                return $this->json(
                    array(
                        'status' => false,
                        'message' => 'Exercise instance is not found!',
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }

            $entityManager->remove($exerciseInstance);
            $entityManager->flush();

            return $this->json(
                array(
                    'status' => true,
                    'message' => 'Exercise instance is removed successfully.',
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