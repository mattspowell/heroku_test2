<?php

/**
 * The appointments test class.
 *
 * @author Joe Savage <joe.savage@theuniprogroup.com>
 */
class AppointmentsCest
{

    /**
     * Tests the appointment diary endpoint
     *
     * @author Joe Savage <joe.savage@theuniprogroup.com>
     * @author Stanislav Proshkin <stanislav.proshkin@theuniprogroup.com>
     * @param ApiTester $I
     */
    public function diary(ApiTester $I)
    {
        $I->wantTo('retrieve available optical appointment slots');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            'index.php/api/v1/webhook', array(
                'id' => '6d13f7ab-104e-4942-9677-329c894ec3ee',
                'timestamp' => '2017-02-27T12:25:46.122Z',
                'lang' => 'en',
                'result' => array(
                    'source' => 'agent',
                    'resolvedQuery' => 'Afternoon',
                    'speech' => '',
                    'action' => 'diary',
                    'actionIncomplete' => false,
                    'parameters' => array(
                        'Timeslot' => 'afternoon',
                    ),
                    'contexts' => array(
                        array(
                            'name' => 'appointment-find-nearby',
                            'parameters' => array(
                                'date' => '2017-03-13',
                                'ukpostcodes' => 'N15',
                                'ukpostcodes.original' => 'N15',
                                'date.original' => '13 march',
                                'Storelist' => 'London - Walthamstow',
                                'Timeslot' => 'afternoon',
                                'Timeslot.original' => 'Afternoon',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 3,
                        ),
                        array(
                            'name' => 'booking',
                            'parameters' => array(
                                'BookingAction.original' => 'Book',
                                'date' => '2017-03-13',
                                'ukpostcodes' => 'N15',
                                'Booking-types' => 'Eye-test 16 or over',
                                'ukpostcodes.original' => 'N15',
                                'date.original' => '13 march',
                                'Storelist' => 'London - Walthamstow',
                                'Booking-types.original' => 'Eye-test 16 or over',
                                'BookingAction' => 'Book ',
                                'Timeslot' => 'afternoon',
                                'Timeslot.original' => 'Afternoon',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 0,
                        ),
                        array(
                            'name' => 'appointment-time',
                            'parameters' => array(
                                'Timeslot' => 'afternoon',
                                'Timeslot.original' => 'Afternoon',
                            ),
                            'lifespan' => 5,
                        ),
                        array(
                            'name' => 'store-select',
                            'parameters' => array(
                                'date' => '2017-03-13',
                                'date.original' => '13 march',
                                'Storelist' => 'London - Walthamstow',
                                'Timeslot' => 'afternoon',
                                'Timeslot.original' => 'Afternoon',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 4,
                        ),
                        array(
                            'name' => 'appointment-type',
                            'parameters' => array(
                                'BookingAction.original' => 'Book',
                                'date' => '2017-03-13',
                                'ukpostcodes' => 'N15',
                                'Booking-types' => 'Eye-test 16 or over',
                                'ukpostcodes.original' => 'N15',
                                'date.original' => '13 march',
                                'Storelist' => 'London - Walthamstow',
                                'Booking-types.original' => 'Eye-test 16 or over',
                                'BookingAction' => 'Book ',
                                'Timeslot' => 'afternoon',
                                'Timeslot.original' => 'Afternoon',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 2,
                        ),
                        array(
                            'name' => 'store',
                            'parameters' => array(
                                'date' => '2017-03-13',
                                'date.original' => '13 march',
                                'Storelist' => 'London - Walthamstow',
                                'stores_collection' => array(
                                    array(
                                        'booking_url' => '/book/woodgreen',
                                        'store_url' => '/stores/woodgreen',
                                        'name' => 'London - Wood Green',
                                        'distance' => '1.5 miles ',
                                    ),
                                    array(
                                        'booking_url' => '/book/crouchend',
                                        'store_url' => '/stores/crouchend',
                                        'name' => 'London - Crouch End',
                                        'distance' => '2.1 miles ',
                                    ),
                                    array(
                                        'booking_url' => '/book/walthamstow',
                                        'store_url' => '/stores/walthamstow',
                                        'name' => 'London - Walthamstow',
                                        'distance' => '2.3 miles ',
                                    ),
                                ),
                                'Timeslot' => 'afternoon',
                                'Timeslot.original' => 'Afternoon',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 12,
                        ),
                        array(
                            'name' => 'appointment-date',
                            'parameters' => array(
                                'date' => '2017-03-13',
                                'date.original' => '13 march',
                                'Timeslot' => 'afternoon',
                                'Timeslot.original' => 'Afternoon',
                            ),
                            'lifespan' => 5,
                        ),
                        array(
                            'name' => 'generic',
                            'parameters' => array(
                                'facebook_sender_id' => '1236654559775795',
                                'Timeslot' => 'afternoon',
                                'Timeslot.original' => 'Afternoon',
                            ),
                            'lifespan' => 4,
                        ),
                    ),
                    'metadata' => array(
                        'intentId' => 'bb023aa5-5dee-407d-855e-675b04f87162',
                        'webhookUsed' => 'true',
                        'webhookForSlotFillingUsed' => 'false',
                        'intentName' => 'Appointment-time',
                    ),
                    'fulfillment' => array(
                        'speech' => 'Fallback',
                        'messages' => array(
                            array(
                                'type' => 0,
                                'speech' => 'Fallback',
                            ),
                        ),
                    ),
                    'score' => 1.0,
                ),
                'status' => array(
                    'code' => 200,
                    'errorType' => 'success',
                ),
                'sessionId' => '87a2d715-147c-41e8-8566-4e2dc116c5cd',
                'originalRequest' => array(
                    'source' => 'facebook',
                    'data' => array(
                        'sender' => array(
                            'id' => '1236654559775795',
                        ),
                        'recipient' => array(
                            'id' => '100973920424428',
                        ),
                        'message' => array(
                            'mid' => 'mid.1488198345877:54f6f1a640',
                            'text' => 'Afternoon',
                            'quick_reply' => array(
                                'payload' => 'Afternoon',
                            ),
                            'seq' => 882,
                        ),
                        'timestamp' => 1.488198345877E12,
                    ),
                ),
            ));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesXpath('//data');
        $I->seeResponseJsonMatchesXpath('//source');
        $I->seeResponseJsonMatchesXpath('//contextOut');
        $I->seeResponseContainsJson([
            "speech" => "Ok, there are available appointments at :",
        ]);
    }

    /**
     * Tests the appointment see-more endpoint
     *
     * @author Stanislav Proshkin <stanislav.proshkin@theuniprogroup.com>
     * @param ApiTester $I
     */

    public function showMore(ApiTester $I)
    {
        $I->wantTo('retrieve available additional appointment slots');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST(
            'index.php/api/v1/webhook', array(
                'id' => 'ddba69ac-2809-4f44-842f-9ad0cd649f3c',
                'timestamp' => '2017-02-27T15:04:58.249Z',
                'lang' => 'en',
                'result' => array(
                    'source' => 'agent',
                    'resolvedQuery' => 'See more',
                    'speech' => '',
                    'action' => 'see-more',
                    'actionIncomplete' => false,
                    'parameters' => array(

                    ),
                    'contexts' => array(
                        array(
                            'name' => 'appointment-find-nearby',
                            'parameters' => array(
                                'date' => '2017-03-13',
                                'ukpostcodes' => 'N15',
                                'ukpostcodes.original' => 'N15',
                                'date.original' => '13 march',
                                'Storelist' => 'London - Walthamstow',
                                'Timeslot' => 'afternoon',
                                'Timeslot.original' => 'Afternoon',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 1,
                        ),
                        array(
                            'name' => 'appointment-time',
                            'parameters' => array(
                                'Timeslot' => 'afternoon',
                                'Timeslot.original' => 'Afternoon',
                            ),
                            'lifespan' => 5,
                        ),
                        array(
                            'name' => 'store-select',
                            'parameters' => array(
                                'date' => '2017-03-13',
                                'date.original' => '13 march',
                                'Storelist' => 'London - Walthamstow',
                                'Timeslot' => 'afternoon',
                                'Timeslot.original' => 'Afternoon',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 2,
                        ),
                        array(
                            'name' => 'appointment-type',
                            'parameters' => array(
                                'BookingAction.original' => 'Book',
                                'date' => '2017-03-13',
                                'ukpostcodes' => 'N15',
                                'Booking-types' => 'Eye-test 16 or over',
                                'ukpostcodes.original' => 'N15',
                                'date.original' => '13 march',
                                'Storelist' => 'London - Walthamstow',
                                'Booking-types.original' => 'Eye-test 16 or over',
                                'BookingAction' => 'Book ',
                                'Timeslot' => 'afternoon',
                                'Timeslot.original' => 'Afternoon',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 0,
                        ),
                        array(
                            'name' => 'store',
                            'parameters' => array(
                                'date' => '2017-03-13',
                                'date.original' => '13 march',
                                'selected_store' => array(
                                    'booking_url' => '/book/walthamstow',
                                    'store_url' => '/stores/walthamstow',
                                    'name' => 'London - Walthamstow',
                                    'distance' => '2.3 miles ',
                                    'store_id' => '270',
                                ),
                                'Storelist' => 'London - Walthamstow',
                                'stores_collection' => array(
                                    array(
                                        'booking_url' => '/book/woodgreen',
                                        'store_url' => '/stores/woodgreen',
                                        'name' => 'London - Wood Green',
                                        'distance' => '1.5 miles ',
                                    ),
                                    array(
                                        'booking_url' => '/book/crouchend',
                                        'store_url' => '/stores/crouchend',
                                        'name' => 'London - Crouch End',
                                        'distance' => '2.1 miles ',
                                    ),
                                    array(
                                        'booking_url' => '/book/walthamstow',
                                        'store_url' => '/stores/walthamstow',
                                        'name' => 'London - Walthamstow',
                                        'distance' => '2.3 miles ',
                                    ),
                                ),
                                'Timeslot' => 'afternoon',
                                'Timeslot.original' => 'Afternoon',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 8,
                        ),
                        array(
                            'name' => 'appointment-date',
                            'parameters' => array(
                                'date' => '2017-03-13',
                                'date.original' => '13 march',
                                'Timeslot' => 'afternoon',
                                'Timeslot.original' => 'Afternoon',
                            ),
                            'lifespan' => 3,
                        ),
                        array(
                            'name' => 'show-more',
                            'parameters' => array(
                                'page' => 1,
                                'strored_timeslots' => array(
                                    array(
                                        'content_type' => 'text',
                                        'title' => '1:15pm',
                                        'payload' => '1:15pm',
                                    ),
                                    array(
                                        'content_type' => 'text',
                                        'title' => '1:25pm',
                                        'payload' => '1:25pm',
                                    ),
                                    array(
                                        'content_type' => 'text',
                                        'title' => '1:25pm',
                                        'payload' => '1:25pm',
                                    ),
                                    array(
                                        'content_type' => 'text',
                                        'title' => '1:30pm',
                                        'payload' => '1:30pm',
                                    ),
                                    array(
                                        'content_type' => 'text',
                                        'title' => '1:40pm',
                                        'payload' => '1:40pm',
                                    ),
                                    array(
                                        'content_type' => 'text',
                                        'title' => '1:50pm',
                                        'payload' => '1:50pm',
                                    ),
                                    array(
                                        'content_type' => 'text',
                                        'title' => '1:50pm',
                                        'payload' => '1:50pm',
                                    ),
                                    array(
                                        'content_type' => 'text',
                                        'title' => '1:50pm',
                                        'payload' => '1:50pm',
                                    ),
                                    array(
                                        'content_type' => 'text',
                                        'title' => '2:05pm',
                                        'payload' => '2:05pm',
                                    ),
                                    array(
                                        'content_type' => 'text',
                                        'title' => '2:15pm',
                                        'payload' => '2:15pm',
                                    ),
                                    array(
                                        'content_type' => 'text',
                                        'title' => '2:15pm',
                                        'payload' => '2:15pm',
                                    ),
                                    array(
                                        'content_type' => 'text',
                                        'title' => '2:20pm',
                                        'payload' => '2:20pm',
                                    ),
                                    array(
                                        'content_type' => 'text',
                                        'title' => '2:30pm',
                                        'payload' => '2:30pm',
                                    ),
                                    array(
                                        'content_type' => 'text',
                                        'title' => '2:40pm',
                                        'payload' => '2:40pm',
                                    ),
                                    array(
                                        'content_type' => 'text',
                                        'title' => '2:40pm',
                                        'payload' => '2:40pm',
                                    ),
                                ),
                            ),
                            'lifespan' => 1,
                        ),
                        array(
                            'name' => 'see-more',
                            'parameters' => array(
                                'Timeslot' => 'afternoon',
                                'Timeslot.original' => 'Afternoon',
                            ),
                            'lifespan' => 5,
                        ),
                        array(
                            'name' => 'generic',
                            'parameters' => array(
                                'facebook_sender_id' => '1236654559775795',
                            ),
                            'lifespan' => 3,
                        ),
                    ),
                    'metadata' => array(
                        'intentId' => '3a8316de-3746-42f1-a39a-a5f2d2bb05c8',
                        'webhookUsed' => 'true',
                        'webhookForSlotFillingUsed' => 'false',
                        'intentName' => 'See more',
                    ),
                    'fulfillment' => array(
                        'speech' => '',
                        'messages' => array(
                            array(
                                'type' => 0,
                                'speech' => '',
                            ),
                        ),
                    ),
                    'score' => 1.0,
                ),
                'status' => array(
                    'code' => 200,
                    'errorType' => 'success',
                ),
                'sessionId' => '87a2d715-147c-41e8-8566-4e2dc116c5cd',
                'originalRequest' => array(
                    'source' => 'facebook',
                    'data' => array(
                        'sender' => array(
                            'id' => '1236654559775795',
                        ),
                        'recipient' => array(
                            'id' => '100973920424428',
                        ),
                        'message' => array(
                            'mid' => 'mid.1488207897903:b279c78e56',
                            'text' => 'See more',
                            'seq' => 960,
                        ),
                        'timestamp' => 1.488207897903E12,
                    ),
                ),
            ));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesXpath('//data');
        $I->seeResponseJsonMatchesXpath('//source');
        $I->seeResponseJsonMatchesXpath('//contextOut');
        $I->seeResponseContainsJson([
            "speech" => "Ok, there are more available appointments at :",
        ]);
    }

    /**
     * Tests the appointment booking endpoint
     *
     * @author Joe Savage <joe.savage@theuniprogroup.com>
     * @author Stanislav Proshkin <stanislav.proshkin@theuniprogroup.com>
     * @param ApiTester $I
     */
    /*
public function book(ApiTester $I)
{
$I->wantTo('make an optical store appointment booking');
$I->haveHttpHeader('Content-Type', 'application/json');
$I->sendPOST(
'index.php/api/v1/webhook', array(
'id' => 'b3d08bfb-e515-45ad-9fd8-55972dcf92eb',
'timestamp' => '2017-02-27T12:26:24.131Z',
'lang' => 'en',
'result' => array(
'source' => 'agent',
'resolvedQuery' => 'Stan Proshkin',
'speech' => '',
'action' => 'make-booking',
'actionIncomplete' => false,
'parameters' => array(
'phone-number' => '07484265674',
'given-name' => 'Stan',
'dob' => '',
'email' => 'stanislav.proshkin@theuniprogroup.com',
'last-name' => '',
),
'contexts' => array(
array(
'name' => 'appointment-time',
'parameters' => array(
'phone-number' => '07484265674',
'dob.original' => '',
'time.original' => '12:00pm',
'email.original' => 'stanislav.proshkin@theuniprogroup.com',
'phone-number.original' => '07484265674',
'Timeslot.original' => 'Afternoon',
'given-name.original' => 'Stan Proshkin',
'last-name.original' => '',
'dob' => '',
'given-name' => 'Stan',
'time' => '12:00:00',
'Timeslot' => 'afternoon',
'email' => 'stanislav.proshkin@theuniprogroup.com',
'last-name' => '',
),
'lifespan' => 1,
),
array(
'name' => 'manual-input',
'parameters' => array(
'given-name.original' => 'Stan Proshkin',
'phone-number' => '07484265674',
'last-name.original' => '',
'dob.original' => '',
'time.original' => '12:00pm',
'email.original' => 'stanislav.proshkin@theuniprogroup.com',
'dob' => '',
'phone-number.original' => '07484265674',
'given-name' => 'Stan',
'time' => '12:00:00',
'email' => 'stanislav.proshkin@theuniprogroup.com',
'last-name' => '',
),
'lifespan' => 5,
),
array(
'name' => 'store',
'parameters' => array(
'phone-number' => '07484265674',
'date' => '2017-03-13',
'dob.original' => '',
'time.original' => '12:00pm',
'email.original' => 'stanislav.proshkin@theuniprogroup.com',
'date.original' => '13 march',
'phone-number.original' => '07484265674',
'selected_store' => array(
'booking_url' => '/book/walthamstow',
'store_url' => '/stores/walthamstow',
'name' => 'London - Walthamstow',
'distance' => '2.3 miles ',
'store_id' => '270',
),
'Storelist' => 'London - Walthamstow',
'stores_collection' => array(
array(
'booking_url' => '/book/woodgreen',
'store_url' => '/stores/woodgreen',
'name' => 'London - Wood Green',
'distance' => '1.5 miles ',
),
array(
'booking_url' => '/book/crouchend',
'store_url' => '/stores/crouchend',
'name' => 'London - Crouch End',
'distance' => '2.1 miles ',
),
array(
'booking_url' => '/book/walthamstow',
'store_url' => '/stores/walthamstow',
'name' => 'London - Walthamstow',
'distance' => '2.3 miles ',
),
),
'Timeslot.original' => 'Afternoon',
'given-name.original' => 'Stan Proshkin',
'last-name.original' => '',
'dob' => '',
'given-name' => 'Stan',
'time' => '12:00:00',
'Timeslot' => 'afternoon',
'email' => 'stanislav.proshkin@theuniprogroup.com',
'last-name' => '',
'Storelist.original' => 'London - Walthamstow',
),
'lifespan' => 5,
),
array(
'name' => 'appointment-date',
'parameters' => array(
'phone-number' => '07484265674',
'date' => '2017-03-13',
'dob.original' => '',
'time.original' => '12:00pm',
'email.original' => 'stanislav.proshkin@theuniprogroup.com',
'date.original' => '13 march',
'phone-number.original' => '07484265674',
'Timeslot.original' => 'Afternoon',
'given-name.original' => 'Stan Proshkin',
'last-name.original' => '',
'dob' => '',
'given-name' => 'Stan',
'time' => '12:00:00',
'Timeslot' => 'afternoon',
'email' => 'stanislav.proshkin@theuniprogroup.com',
'last-name' => '',
),
'lifespan' => 0,
),
array(
'name' => 'appointment-user-information',
'parameters' => array(
'phone-number' => '07484265674',
'given-name.original' => 'Stan Proshkin',
'last-name.original' => '',
'dob.original' => '',
'email.original' => 'stanislav.proshkin@theuniprogroup.com',
'dob' => '',
'phone-number.original' => '07484265674',
'given-name' => 'Stan',
'email' => 'stanislav.proshkin@theuniprogroup.com',
'last-name' => '',
),
'lifespan' => 5,
),
array(
'name' => 'generic',
'parameters' => array(
'phone-number' => '07484265674',
'dob.original' => '',
'time.original' => '12:00pm',
'email.original' => 'stanislav.proshkin@theuniprogroup.com',
'phone-number.original' => '07484265674',
'Timeslot.original' => 'Afternoon',
'given-name.original' => 'Stan Proshkin',
'last-name.original' => '',
'dob' => '',
'given-name' => 'Stan',
'time' => '12:00:00',
'facebook_sender_id' => '1236654559775795',
'Timeslot' => 'afternoon',
'email' => 'stanislav.proshkin@theuniprogroup.com',
'last-name' => '',
),
'lifespan' => 2,
),
array(
'name' => 'appointment-confirmation',
'parameters' => array(
'given-name.original' => 'Stan Proshkin',
'phone-number' => '07484265674',
'last-name.original' => '',
'dob.original' => '',
'time.original' => '12:00pm',
'email.original' => 'stanislav.proshkin@theuniprogroup.com',
'dob' => '',
'phone-number.original' => '07484265674',
'given-name' => 'Stan',
'time' => '12:00:00',
'email' => 'stanislav.proshkin@theuniprogroup.com',
'last-name' => '',
),
'lifespan' => 1,
),
array(
'name' => 'facebook-input',
'parameters' => array(
'given-name.original' => 'Stan Proshkin',
'phone-number' => '07484265674',
'last-name.original' => '',
'dob.original' => '',
'time.original' => '12:00pm',
'email.original' => 'stanislav.proshkin@theuniprogroup.com',
'dob' => '',
'phone-number.original' => '07484265674',
'given-name' => 'Stan',
'time' => '12:00:00',
'email' => 'stanislav.proshkin@theuniprogroup.com',
'last-name' => '',
),
'lifespan' => 1,
),
),
'metadata' => array(
'intentId' => '2c5e4ad5-25d0-460f-974e-e64ce54b3862',
'webhookUsed' => 'true',
'webhookForSlotFillingUsed' => 'false',
'intentName' => 'Appointment-user-information',
),
'fulfillment' => array(
'speech' => 'Thank you we can confirm that this is all set. We will send you a confirmation email shortly.',
'messages' => array(
array(
'type' => 0,
'speech' => 'Thank you we can confirm that this is all set. We will send you a confirmation email shortly.',
),
),
),
'score' => 1.0,
),
'status' => array(
'code' => 200,
'errorType' => 'success',
),
'sessionId' => '87a2d715-147c-41e8-8566-4e2dc116c5cd',
'originalRequest' => array(
'source' => 'facebook',
'data' => array(
'sender' => array(
'id' => '1236654559775795',
),
'recipient' => array(
'id' => '100973920424428',
),
'message' => array(
'mid' => 'mid.1488198383839:33462d6942',
'text' => 'Stan Proshkin',
'seq' => 907,
),
'timestamp' => 1.488198383839E12,
),
),
));
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseJsonMatchesXpath('//speech');
$I->seeResponseJsonMatchesXpath('//displayText');
$I->seeResponseJsonMatchesXpath('//data');
$I->seeResponseJsonMatchesXpath('//contextOut');
$I->seeResponseJsonMatchesXpath('//source');
}
 */
}
