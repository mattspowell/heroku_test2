<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**

 * The webhooks controller class.
 *
 * @author Joe Savage <joe.savage@theuniprogroup.com>
 * @author Stanislav Proskin <stanislav.proshkin@theuniprogroup.com>
 */
class WebhooksController
{

    /**
     * @var object $storesService Stores service
     */
    protected $storesService;

    /**
     * @var object $appointmentsService Appointments service
     */
    protected $appointmentsService;

    /**
     * @var object $app Silex Application object
     */
    protected $app;

    public function __construct($storesService, $appointmentsService, $app)
    {
        $this->storesService = $storesService;
        $this->appointmentsService = $appointmentsService;
        $this->app = $app;
    }

    /**
     * Determine the appropriate response based on the action provided in the request.
     *
     * @author Joe Savage <joe.savage@theuniprogroup.com>
     * @author Stanislav Proskin <stanislav.proshkin@theuniprogroup.com>
     * @param Request $request A request object
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        // get the result param
        $result = $request->request->get('result');

        // get the action
        $action = $result['action'];

        // throw an error
        if (empty($action)) {
        }

        // set the store URL for store info requests from contexts
        if ($action == "store-telephone-number" || $action == "store-manager-name" || $action == "store-opening-hours") {
            $storeInformation = array();
            foreach ($result['contexts'] as $context) {
                if ($context['name'] == "store") {
                        $storesCollection = $context['parameters']['stores_collection'];
                        if (is_array($storesCollection)) {
                            foreach ($storesCollection as $value) {
                                if ($value['name'] == $context['parameters']['Storelist']) {
                                    $storeInformation = $value;
                                }
                            }
                        }
                }
            }
        }

        // set the address for store location searches from contexts
        if ($action == "find-nearby" || $action == "find-nearby-store-information") {
            $address = $result['parameters']['ukpostcodes'];
        }

        // process the action
        switch ($action) {
            case "find-nearby":
            case "find-nearby-store-information":
                $response = $this->storesService->findNearby($address);
                break;
            case "store-telephone-number":
                $response = $this->storesService->storeTelephoneNumber($storeInformation);
                break;
            case "store-manager-name":
                $response = $this->storesService->storeManagerName($storeInformation);
                break;
            case "store-opening-hours":
                $response = $this->storesService->storeOpeningHours($storeInformation);
                break;
            case "diary":
                // timeslot from the parameters
                $timeSlot = $result['parameters']['Timeslot'];
                // get the storeId from contexts in the result
                $storeId = null;
                foreach ($result['contexts'] as $context) {
                    if ($context['name'] == "store") {
                        $storesCollection = $context['parameters']['stores_collection'];
                        $date = $context['parameters']['date'];
                        if (is_array($storesCollection)) {
                            foreach ($storesCollection as $value) {
                                if ($value['name'] == $context['parameters']['Storelist']) {
                                    $storeInformation = $value;
                                }
                            }
                        }
                    }
                }
                // check the store appointments diary
                $response = $this->appointmentsService->diary($date, $timeSlot, $storeInformation);
                break;
            case "see-more":
                $appointments = array(); $offset = 1;
                foreach ($result['contexts'] as $context) {
                    if ($context['name'] == "show-more") {
                        $appointments = $context['parameters']['strored_timeslots'];
                        $offset = $context['parameters']['page'];
                    }
                }
                $response = $this->appointmentsService->showMore($appointments, $offset);
                break;
            case "hold":
                // get the date and time from contexts in the request
                $dateTime = "";
                foreach ($result['contexts'] as $context) {
                    if ($context['name'] == "datetime") {
                        $dateTime = $context['parameters']['date'] . " " . $context['parameters']['time'];
                    }
                }
                // hold the appointment
                $response = $this->appointmentsService->hold($dateTime);
                break;
            case "make-booking":
                // get the email from contexts
                $email = $phoneNumber = $userFullName = $formattedDate = $time = "";
                $storeInformation = array();
                $email = $result['parameters']['email'];
                $phoneNumber = $result['parameters']['phone-number'];
                $userFullName = $result['parameters']['given-name'] . ' ' . $result['parameters']['last-name'];
                foreach ($result['contexts'] as $context) {
                    if ($context['name'] == "store") {
                        if (is_array($context['parameters']['selected_store']) &&
                            sizeof($context['parameters']['selected_store']) > 0) {
                            $formattedDate = date("l jS F", strtotime($context['parameters']['date']));
                            $time = $context['parameters']['time.original'];
                            $storeInformation = $context['parameters']['selected_store'];
                        }
                    }
                }
                // get the app
                $app = $this->app;
                // book the appointment
                $response = $this->appointmentsService->book($app, $email, $phoneNumber, $userFullName, $formattedDate, $time, $storeInformation);
                break;
            default:
                // throw an error
        }
        return new JsonResponse($response);
    }

}
