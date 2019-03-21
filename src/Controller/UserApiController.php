<?php
/**
 * Created by IntelliJ IDEA.
 * User: mert
 * Date: 3/16/19
 * Time: 1:54 PM
 */

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\FormValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserApiController
 * @package App\Controller
 * @Route("/api/user")
 */
class UserApiController extends AbstractController
{
    private $images = array(
        'Female' => 'dist/img/user7-128x128.jpg',
        'Male' => 'dist/img/user1-128x128.jpg'
    );

    /**
     * @Route("/get-all", name="user_get_all", methods={"GET"})
     * @param UserRepository $userRepository
     * @return Response
     */
    public function getAll(UserRepository $userRepository): Response
    {
        try {


            $users = $userRepository->findAll();
            return $this->json(
                array(
                    'status' => true,
                    'message' => '',
                    'data' => $users
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
     * @Route("/get/{id}", name="user_get", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function getOne(User $user = NULL): Response
    {
        try {
            if (empty($user)) {
                return $this->json(
                    array(
                        'status' => false,
                        'message' => 'User is not found!',
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }


            return $this->json(
                array(
                    'status' => true,
                    'message' => '',
                    'data' => $user
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
     * @Route("/add", name="user_add_post", methods={"POST"})
     * @param Request $request
     * @param FormValidatorService $formValidatorService
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function add(Request $request, FormValidatorService $formValidatorService, EntityManagerInterface $entityManager): Response
    {
        try {
            $data = array();
            $data['firstName'] = $request->request->get('first-name', null);
            $data['lastName'] = $request->request->get('last-name', null);
            $data['email'] = $request->request->get('email', null);
            $data['phone'] = $request->request->get('phone', null);
            $data['birthDay'] = $request->request->get('birth-day', null);
            $data['gender'] = $request->request->get('gender', null);

            $isValid = $formValidatorService->checkUserData($data);

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

            $user = new User();
            $user->setFirstName($data['firstName']);
            $user->setLastName($data['lastName']);
            $user->setEmail($data['email']);
            $user->setPhone($data['phone']);
            $user->setBirthDay(new \DateTime($data['birthDay']));
            $user->setGender($data['gender']);
            $user->setImage($this->images[$data['gender']]);
            $user->setCreatedAt(new \DateTime());

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json(
                array(
                    'status' => $isValid['status'],
                    'message' => 'User is added successfully.',
                    'data' => $user
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
     * @Route("/edit/{id}", name="user_edit_post", methods={"POST"})
     * @param User $user
     * @param Request $request
     * @param FormValidatorService $formValidatorService
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(User $user = NULL, Request $request, FormValidatorService $formValidatorService, EntityManagerInterface $entityManager): Response
    {
        try {

            if (empty($user)) {
                return $this->json(
                    array(
                        'status' => false,
                        'message' => 'User is not found!',
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }

            $data = array();
            $data['firstName'] = $request->request->get('first-name', null);
            $data['lastName'] = $request->request->get('last-name', null);
            $data['email'] = $request->request->get('email', null);
            $data['phone'] = $request->request->get('phone', null);
            $data['birthDay'] = $request->request->get('birth-day', null);
            $data['gender'] = $request->request->get('gender', null);

            $isValid = $formValidatorService->checkUserData($data);

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

            $user->setFirstName($data['firstName']);
            $user->setLastName($data['lastName']);
            $user->setEmail($data['email']);
            $user->setPhone($data['phone']);
            $user->setGender($data['gender']);
            $user->setImage($this->images[$data['gender']]);
            $user->setBirthDay(new \DateTime($data['birthDay']));

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json(
                array(
                    'status' => $isValid['status'],
                    'message' => 'User is updated successfully.',
                    'data' => $user
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
     * @Route("/remove/{id}", name="user_remove", methods={"GET"})
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function remove(User $user = NULL, EntityManagerInterface $entityManager): Response
    {
        try {
            if (empty($user)) {
                return $this->json(
                    array(
                        'status' => false,
                        'message' => 'User is not found!',
                        'data' => array()
                    ),
                    Response::HTTP_OK
                );
            }

            $entityManager->remove($user);
            $entityManager->flush();

            return $this->json(
                array(
                    'status' => true,
                    'message' => 'User is removed successfully.',
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