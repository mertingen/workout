<?php
/**
 * Created by IntelliJ IDEA.
 * User: mert
 * Date: 3/17/19
 * Time: 7:43 PM
 */

namespace App\Service;


use App\Entity\Plan;
use App\Entity\User;
use SendGrid\Mail\Mail;

class NotificationService
{

    private $sendGrid;

    public function __construct(Mail $sendGrid)
    {
        $this->sendGrid = $sendGrid;
    }

    public function send(array $data = array())
    {
        try {
            $this->sendGrid->setFrom($data['from']);
            $this->sendGrid->addTo($data['to']);
            $this->sendGrid->setSubject($data['subject']);
            $this->sendGrid->addContent(
                "text/html", $data['body']
            );
            $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

            $response = $sendgrid->send($this->sendGrid);
            if ($response->statusCode() == 202) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
            //echo 'Caught exception: '. $e->getMessage() ."\n";
        }
    }

    public function getPlanConfirmationBody(User $user, Plan $plan, $confirmationUrl = ''): string
    {
        return '<html>
                       <body>
                            Hi ' . $user->getFirstName() . ',<br><br>' .
            'You were assigned to ' . $plan->getName() . ' workout plan. If you would like to attend that please confirm that plan.<br><br>
                            <a href="' . $confirmationUrl . '/' . $user->getId() . '/' . $plan->getId() . '">CONFIRM</a></body>
                 </html>';
    }

    public function getPlanEditedBody(User $user, Plan $plan): string
    {
        return '<html>
                       <body>
                            Hi ' . $user->getFirstName() . ',<br><br>' .
            $plan->getName() . ' workout plan is changed. You could see your new plan the following.<br><br>' .
            'Name: ' . $plan->getName() . ' <br>' .
            'Description: ' . $plan->getDescription() . ' <br>' .
            'Difficulty:' . $plan->getDifficulty() .

            ' </html> ';
    }

    public function getPlanRemovedBody(User $user, Plan $plan): string
    {
        return ' <html>
                       <body>
    Hi ' . $user->getFirstName() . ',<br><br> ' .
            $plan->getName() . ' workout plan is removed . Have a good day . ' .
            ' </html > ';
    }

}