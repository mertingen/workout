<?php
/**
 * Created by IntelliJ IDEA.
 * Exercise: mert
 * Date: 3/16/19
 * Time: 1:54 PM
 */

namespace App\Controller;


use App\Entity\Exercise;
use App\Repository\ExerciseRepository;
use App\Service\FormValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ExerciseApiController
 * @package App\Controller
 * @Route("/api/exercise")
 */
class ExerciseApiController extends AbstractController
{
    /**
     * @Route("/get-all", name="exercise_get_all", methods={"GET"})
     * @param ExerciseRepository $exerciseRepository
     * @return Response
     */
    public function getAll(ExerciseRepository $exerciseRepository): Response
    {
        try {
            $exercises = $exerciseRepository->findAll();
            return $this->json(
                array(
                    'status' => true,
                    'message' => '',
                    'data' => $exercises
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
                Response::HTTP_OK
            );
        }
    }

    /**
     * @Route("/get/{id}", name="exercise_get", methods={"GET"})
     * @param Exercise $exercise
     * @return Response
     */
    public function getOne(Exercise $exercise = NULL): Response
    {
        try {
            if (empty($exercise)) {
                return $this->json(
                    array(
                        'status' => false,
                        'message' => 'Exercise is not found!',
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }


            return $this->json(
                array(
                    'status' => true,
                    'message' => '',
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
     * @Route("/add", name="exercise_add_post", methods={"POST"})
     * @param Request $request
     * @param FormValidatorService $formValidatorService
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function add(Request $request, FormValidatorService $formValidatorService, EntityManagerInterface $entityManager): Response
    {
        try {
            $data = array();
            $data['name'] = $request->request->get('name', null);

            $isValid = $formValidatorService->checkExerciseData($data);

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

            $exercise = new Exercise();
            $exercise->setName($data['name']);

            $entityManager->persist($exercise);
            $entityManager->flush();

            return $this->json(
                array(
                    'status' => $isValid['status'],
                    'message' => 'Exercise is added successfully.',
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
     * @Route("/edit/{id}", name="exercise_edit_post", methods={"POST"})
     * @param Exercise $exercise
     * @param Request $request
     * @param FormValidatorService $formValidatorService
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Exercise $exercise = NULL, Request $request, FormValidatorService $formValidatorService, EntityManagerInterface $entityManager): Response
    {
        try {

            if (empty($exercise)) {
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
            $data['name'] = $request->request->get('name', null);

            $isValid = $formValidatorService->checkExerciseData($data);

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

            $exercise->setName($data['name']);

            $entityManager->persist($exercise);
            $entityManager->flush();

            return $this->json(
                array(
                    'status' => $isValid['status'],
                    'message' => 'Exercise is updated successfully.',
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
     * @Route("/remove/{id}", name="remove_exercise", methods={"GET"})
     * @param Exercise $exercise
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function remove(Exercise $exercise = NULL, EntityManagerInterface $entityManager): Response
    {
        try {
            if (empty($exercise)) {
                return $this->json(
                    array(
                        'status' => false,
                        'message' => 'Exercise is not found!',
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }

            $entityManager->remove($exercise);
            $entityManager->flush();

            return $this->json(
                array(
                    'status' => true,
                    'message' => 'Exercise is removed successfully.',
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