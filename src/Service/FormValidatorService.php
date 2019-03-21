<?php
/**
 * Created by IntelliJ IDEA.
 * User: mert
 * Date: 3/15/19
 * Time: 12:59 AM
 */

namespace App\Service;


/**
 * Class FormValidatorService
 * @package App\Service
 *
 * This service is working to control regarding form data.
 */
class FormValidatorService
{
    /**
     * @param array $data
     * @return array
     */
    public function checkUserData(array $data = array()): array
    {
        if (empty($data['firstName']) ||
            empty($data['lastName']) ||
            empty($data['email']) ||
            empty($data['gender']) ||
            empty($data['phone']) ||
            empty($data['birthDay'])) {
            return array(
                'status' => false,
                'message' => 'Values are must be sent.'
            );
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return array(
                'status' => false,
                'message' => 'Enter a valid e-mail address, please.'
            );
        }

        return array(
            'status' => true
        );

    }

    /**
     * @param array $data
     * @return array
     */
    public function checkPlanData(array $data = array()): array
    {
        if (empty($data['name']) ||
            empty($data['description']) ||
            empty($data['userIds']) ||
            empty($data['difficulty'])) {
            return array(
                'status' => false,
                'message' => 'Values are must be sent.'
            );
        }

        return array(
            'status' => true
        );

    }

    /**
     * @param array $data
     * @return array
     */
    public function checkPlanDayData(array $data = array()): array
    {
        if (empty($data['name']) ||
            empty($data['planId']) ||
            empty($data['order'])) {
            return array(
                'status' => false,
                'message' => 'Values are must be sent.'
            );
        }

        return array(
            'status' => true
        );

    }

    /**
     * @param array $data
     * @return array
     */
    public function checkExerciseData(array $data = array()): array
    {
        if (empty($data['name'])) {
            return array(
                'status' => false,
                'message' => 'Values are must be sent.'
            );
        }

        return array(
            'status' => true
        );

    }

    /**
     * @param array $data
     * @return array
     */
    public function checkExerciseInstanceData(array $data = array()): array
    {
        if (empty($data['duration']) ||
            empty($data['order']) ||
            empty($data['planDayId']) ||
            empty($data['exerciseId'])) {
            return array(
                'status' => false,
                'message' => 'Values are must be sent.'
            );
        }

        return array(
            'status' => true
        );

    }

}