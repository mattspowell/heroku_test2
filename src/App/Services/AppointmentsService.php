<?php

namespace App\Services;

use GoogleMapsGeocoder;
use Goutte\Client;
use GuzzleHttp;

/**
 * The appointments service class.
 *
 * @author Joe Savage <joe.savage@theuniprogroup.com>
 */
class AppointmentsService
{

    /**
     * Query the Specsavers brand site to fetch free optical appointments.
     *
     * @author Joe Savage <joe.savage@theuniprogroup.com>
     * @author Stanislav Proshkin <stanislav.proshkin@theuniprogroup.com>
     * @param string $date
     * @param string $timeSlot
     * @param array $storeInformation
     *
     * @return array
     */
    public function diary($date = "", $timeSlot = "", $storeInformation = array())
    {
        // set common response vars
        $response = array(
            'source' => 'facebook-ss-sas-bot-web-services',
            'data' => new \stdClass,
            'contextOut' => array(),
        );

        $client = new Client();
        $crawler = $client->request('GET', 'https://www.specsavers.co.uk' . $storeInformation['booking_url']);
        $storeInformation['store_id'] = $storeId = $crawler->filter('#appointment-type--section')->attr('data-store-id');

        // set appointment type to age 16 or over
        $apType = 2000;
        // set the room Id to 99, this has to be set due to legacy issues
        $roomId = 99;
        // set days to 0 so only the provided day's appointments are returned
        $days = 0;

        // query the diary endpoint to get available appointments
        $client = new GuzzleHttp\Client();
        $diarySearchResponse = $client->request('GET', 'https://www.specsavers.co.uk/api/appointments/diary/search', [
            'debug' => false,
            'query' => array(
                'date_req' => $date,
                'ap_type' => $apType,
                'room_id' => $roomId,
                'days' => $days,
                'json' => null,
                'store_id' => $storeId,
            ),
        ]);

        // an error occurred when requesting the appointments diary
        $diarySearchResponseCode = $diarySearchResponse->getStatusCode();
        if ($diarySearchResponseCode !== 200) {
            $response['speech'] = "We are unable to fetch available appointments at the moment. Please try again later.";
            $response['displayText'] = $response['speech'];
            return $response;
        }

        // get the appointments for the time slot specified
        $diarySearchResponseBodyJson = json_decode($diarySearchResponse->getBody(), true);

        $timeSlotAppointments = array();
        foreach ($diarySearchResponseBodyJson['data'] as $k => $v) {
            $timeSlotAppointments = $v[$timeSlot];
        }

        // format the date
        $dateFormatted = date('jS F Y', strtotime($date));

        // build the speech response
        if (empty($timeSlotAppointments)) {
            $response['speech'] = "I'm sorry. There are no available appointments on " . $dateFormatted . " for the " . $timeSlot . " time slot.";
        } else {
            $response['speech'] = "Ok, there are available appointments at :";
        }

        // preparing data for facebook messenger
        $response['data'] = array(
            'facebook' => array(
                'text' => $response['speech'],
            ),
        );

        $count = 1;
        $storedForLater = $alreadyUsed = array();

        if (!empty($timeSlotAppointments)) {
            // making quick replys buttons and limit it to only 10 replys
            foreach ($timeSlotAppointments as $value) {
                if (in_array($value['start_time'], $alreadyUsed)) {
                    continue;
                }

                if ($count == 10) {
                    $response['data']['facebook']['quick_replies'][] = array(
                        'content_type' => 'text',
                        'title' => 'See more...',
                        'payload' => 'See more',
                    );
                } else if ($count > 10) {
                    $storedForLater[] = array(
                        'content_type' => 'text',
                        'title' => $value['start_time'],
                        'payload' => $value['start_time'],
                    );
                } else {
                    $response['data']['facebook']['quick_replies'][] = array(
                        'content_type' => 'text',
                        'title' => $value['start_time'],
                        'payload' => $value['start_time'],
                    );
                }
                $alreadyUsed[] = $value['start_time'];
                $count++;
            }
        }

        $response['contextOut'][] = (object) array(
            'name' => 'store',
            'lifespan' => 10,
            'parameters' => array(
                'selected_store' => $storeInformation,
            ),
        );
        if (sizeof($storedForLater) > 0) {
            $response['contextOut'][] = (object) array(
                'name' => 'show-more',
                'lifespan' => 3,
                'parameters' => array(
                    'strored_timeslots' => $storedForLater,
                    'page' => 1,
                ),
            );
        }

        // return the response
        //$response['displayText'] = $response['speech'];
        return $response;
    }

    /**
     * Show additional appointment times if requested
     *
     * @author Joe Savage <joe.savage@theuniprogroup.com>
     * @author Stanislav Proshkin <stanislav.proshkin@theuniprogroup.com>
     * @param int $page
     * @param array $storedContexts
     * @return array
     */
    public function showMore($storedAppointments = array(), $page = 1)
    {
        // set common response vars
        $response = array(
            'source' => 'facebook-ss-sas-bot-web-services',
            'data' => new \stdClass,
            'contextOut' => array(),
        );
        // slicing the array with prepared quick responses
        $offset = 0;
        if ($page > 1) {
            $offset = ($page - 1) * 9;
        }
        $slisedAppointments = array_slice($storedAppointments, $offset, 9);

        // build the speech response
        if (empty($storedAppointments) || empty($slisedAppointments)) {
            $response['speech'] = "I'm sorry. There are no available appointments.";
        } else {
            $response['speech'] = "Ok, there are more available appointments at :";
        }

        // preparing data for facebook messenger
        $response['data'] = array(
            'facebook' => array(
                'text' => $response['speech'],
                'quick_replies' => $slisedAppointments,
            ),
        );
        $page++;
        $response['contextOut'][] = (object) array(
            'name' => 'show-more',
            'lifespan' => 3,
            'parameters' => array(
                'page' => $page,
            ),
        );
        return $response;
    }

    /**
     * Mock an appointment reservation.
     *
     * @author Joe Savage <joe.savage@theuniprogroup.com>
     * @author Stanislav Proshkin <stanislav.proshkin@theuniprogroup.com>
     * @param string $dateTime
     * @return array
     */
    public function hold($dateTime = "")
    {
        // format the date (this needs to be fixed!)
        $dateFormatted = date('jS F Y g:ia', strtotime($dateTime));

        // build the response
        $response = 'Ok, I have reserved this for ' . $dateTime . '. To complete the booking I just need a few details. Firstly, are you entitled to an NHS eye test? If you\'re not sure just say not sure.';

        // return the response
        return array(
            'speech' => $response,
            'displayText' => $response,
            'source' => 'facebook-ss-sas-bot-web-services',
            'data' => new \stdClass,
            'contextOut' => array(),
        );
    }

    /**
     * Mock an appointment booking.
     *
     * @author Joe Savage <joe.savage@theuniprogroup.com>
     * @author Stanislav Proshkin <stanislav.proshkin@theuniprogroup.com>
     * @param array $storeInformation
     * @param string $time
     * @param string $formattedDate
     * @param string $userFullName
     * @param string $phoneNumber
     * @param string $email
     * @param object $app Silex Application object
     * @return array
     */
    public function book($app, $email = "", $phoneNumber = '', $userFullName = '', $formattedDate = '', $time = '', $storeInformation = array())
    {
        $gmapLink = $storeName = $requestUrl = '';

        if (is_array($storeInformation) && !empty($storeInformation)) {
            // Generating a link to the store
            $requestUrl = 'https://www.specsavers.co.uk' . $storeInformation['store_url'];

            $client = new Client();
            $crawler = $client->request('GET', $requestUrl);
            $clientResponse = $client->getResponse();

            // get the store full address from page
            // $storeStreetText = $crawler->filter('div.store-details div.field-name-field-store-address div.street-block')->first()->text();
            // $storeCityText = $crawler->filter('div.store-details div.field-name-field-store-address div.city-block')->first()->text();
            // $storePostcodeText = $crawler->filter('div.store-details div.field-name-field-store-address div.locality-block')->first()->text();
            // $storeAddressText = $storeStreetText . ', ' . $storeCityText . ' ' . $storePostcodeText;
            $storeAddressText = 'Specsavers Opticians ' . $storeInformation['name'];
            $storeAddressEsc = str_replace(' ', '+', $storeAddressText);
            $geocoder = new \GoogleMapsGeocoder($storeAddressText);
            $geocoder->setRegion("uk");
            $geocoderResponse = $geocoder->geocode();
            $gmapLink = '';
            if (count($geocoderResponse['results'])) {
                // get the location data from the results
                $location = $geocoderResponse['results'][0];
                $lat = $location['geometry']['location']['lat'];
                $long = $location['geometry']['location']['lng'];
                $gmapLink = 'https://www.google.co.uk/maps/place/' . $storeAddressEsc . '/@' . $lat . ',' . $long . 'z';
            }
            $storeName = $storeInformation['name'];
        }

        // generating messages bodies
        $emailBody = "Hi " . $userFullName . "\n\nThank you for using the Specsavers Booking Bot. We have booked you in for an appointment at " . $time . " on " . $formattedDate . " at " . $storeName . " " . $gmapLink . ".\n\nIf you’d like to contact us regarding this appointment please call us on 020 8542 4434.\n\nWe look forward to seeing you soon,\n\nThe " . $storeName . " Specsavers Team.";
        $textBody = "Hi " . $userFullName . ", thank you for booking with Specsavers. We look forward to seeing you at " . $time . " on " . $formattedDate . ". " . $storeName;
        $responseBody = "Thank you, your appointment has been booked for " . $time . " on " . $formattedDate . " at " . $storeName . " " . $gmapLink . ".";

        if (!empty($email)) {
            // send the email
            $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
                ->setUsername('sssasbotwebservices@gmail.com')
                ->setPassword('12Testin');
            $mailer = \Swift_Mailer::newInstance($transport);
            $logger = new \Swift_Plugins_Loggers_ArrayLogger();
            $mailer->registerPlugin(new \Swift_Plugins_LoggerPlugin($logger));
            $message = \Swift_Message::newInstance()
                ->setSubject("Specsavers appointment")
                ->setFrom(array("sssasbotwebservices@gmail.com"))
                ->setTo(array($email))
                ->setBody($emailBody);
            $app['swiftmailer.use_spool'] = false;
            $mailer->send($message, $failures);
            // debug email sending if needed
            /*
        if (!$mailer->send($message, $failures)) {
        var_dump($logger->dump());
        }
         */
        }

        // sending text if phone number is present
        if (!empty($phoneNumber)) {
            $this->sendSms($app, $phoneNumber, $textBody);
        }

        // build the response
        $response = $responseBody;

        $facebookButtons = array(
            'facebook' => array(
                'attachment' => array(
                    'type' => 'template',
                    'payload' => array(
                        'template_type' => 'button',
                        'text' => $responseBody,
                        'buttons' => array(
                            array(
                                'type' => 'web_url',
                                'url' => $requestUrl,
                                'title' => 'View Online Store',
                            ),
                            array(
                                'type' => 'postback',
                                'title' => 'Goodbye!',
                                'payload' => 'Goodbye!',
                            ),
                        ),
                    ),
                ),
            ),
        );
        // return the response
        return array(
            'speech' => $response,
            'displayText' => $response,
            'source' => 'facebook-ss-sas-bot-web-services',
            'data' => $facebookButtons,
            'contextOut' => array(),
        );
    }

    /**
     * Helper function to send sms instead of or with email.
     *
     * If SMS was sent correctly it should return a numeric string with credits left, or
     * if 'returnid' param was set to true - return tracking number of message.
     * Added just in case we will need a possibility to check message status in future.
     *
     * @author Stanislav Proshkin <stanislav.proshkin@theuniprogroup.com>
     * @param string $number
     * @param string $message
     * @param object $app Silex Application object
     * @return string
     */
    protected function sendSms($app, $number = '', $message = '')
    {
        // checking if configs exists
        if (empty($number) && empty($message)) {
            return 'ERROR';
        }
        if (!isset($app['kapow']) || empty($app['kapow']['endpoint']) || empty($app['kapow']['username']) || empty($app['kapow']['password'])) {
            return 'ERROR';
        }
        // preparing params to send
        $query = array(
            'username' => $app['kapow']['username'],
            'password' => $app['kapow']['password'],
            'mobile' => $number,
            'sms' => $message,
        );
        if (isset($app['kapow']['returnid']) && $app['kapow']['returnid']) {
            $query['returnid'] = 'TRUE';
        }

        // making request to kapow and fetch response
        $client = new GuzzleHttp\Client();
        $kapow = $client->request('GET', $app['kapow']['endpoint'], [
            'debug' => false,
            'query' => $query,
        ]);
        // return error if connection was interrupted
        if ($kapow->getStatusCode() != 200) {
            return 'ERROR';
        }
        $kapowResponseBody = $kapow->getBody()->read(1024);
        // TODO: add some response codes handler
        switch ($kapowResponseBody) {
            case 'USERPASS':
                $returnMessage = 'ERROR';
                break;
            case 'NOCREDIT':
                $returnMessage = 'ERROR';
                break;
            case 'ERROR':
                $returnMessage = 'ERROR';
                break;
            default:
                $returnMessage = 'ERROR';
                if (strpos($kapowResponseBody, 'OK') !== false) {
                    $responseChanks = explode(' ', $kapowResponseBody);
                    $returnMessage = end($responseChanks);
                }
                break;
        }
        // return actual kapow response
        return $returnMessage;
    }

}
