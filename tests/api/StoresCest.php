<?php

/**
 * The stores test class.
 *
 * @author Joe Savage <joe.savage@theuniprogroup.com>
 */
class StoresCest
{

    /**
     * Tests the store find nearby endpoint
     *
     * @author Stanislav Proskin <stanislav.proshkin@theuniprogroup.com>
     * @param ApiTester $I
     */
    public function findNearBy(ApiTester $I)
    {
        $I->wantTo('find the nearest Specsavers store');
        $I->haveHttpHeader('Content-Type', 'application/json');
        // see docs at docs.api.ai/docs/webhook for request and response params
        $I->sendPOST(
            'index.php/api/v1/webhook', array(
                'id' => 'a10379d1-1a33-41c8-aae9-d00d3599a83c',
                'timestamp' => '2017-02-22T16 =>10 =>45.571Z',
                'lang' => 'en',
                'result' => array(
                    'source' => 'agent',
                    'resolvedQuery' => 'n15',
                    'speech' => '',
                    'action' => 'find-nearby',
                    'actionIncomplete' => false,
                    'parameters' => array(
                        'ukpostcodes' => 'N15',
                    ),
                    'contexts' => array(
                        array(
                            'name' => 'appointment-find-nearby',
                            'parameters' => array(
                                'ukpostcodes' => 'N15',
                                'ukpostcodes.original' => 'n15',
                            ),
                            'lifespan' => 5,
                        ),
                        array(
                            'name' => 'booking',
                            'parameters' => array(
                                'ukpostcodes' => 'N15',
                                'BookingAction.original' => 'Book',
                                'Booking-types' => 'Eye-test 16 or over',
                                'Greeting' => 'Hi',
                                'ukpostcodes.original' => 'n15',
                                'Greeting.original' => 'hi',
                                'Booking-types.original' => 'Eye-test 16 or over',
                                'BookingAction' => 'Book ',
                            ),
                            'lifespan' => 4,
                        ),
                        array(
                            'name' => 'greeting',
                            'parameters' => array(
                                'ukpostcodes' => 'N15',
                                'BookingAction.original' => 'Book',
                                'Booking-types' => 'Eye-test 16 or over',
                                'Greeting' => 'Hi',
                                'ukpostcodes.original' => 'n15',
                                'Greeting.original' => 'hi',
                                'Booking-types.original' => 'Eye-test 16 or over',
                                'BookingAction' => 'Book ',
                            ),
                            'lifespan' => 2,
                        ),
                        array(
                            'name' => 'appointment-type',
                            'parameters' => array(
                                'ukpostcodes' => 'N15',
                                'BookingAction.original' => 'Book',
                                'Booking-types' => 'Eye-test 16 or over',
                                'ukpostcodes.original' => 'n15',
                                'Booking-types.original' => 'Eye-test 16 or over',
                                'BookingAction' => 'Book ',
                            ),
                            'lifespan' => 5,
                        ),
                        array(
                            'name' => 'store-information',
                            'parameters' => array(
                                'ukpostcodes' => 'N15',
                                'BookingAction.original' => 'Book',
                                'Booking-types' => 'Eye-test 16 or over',
                                'Greeting' => 'Hi',
                                'ukpostcodes.original' => 'n15',
                                'Greeting.original' => 'hi',
                                'Booking-types.original' => 'Eye-test 16 or over',
                                'BookingAction' => 'Book ',
                            ),
                            'lifespan' => 2,
                        ),
                        array(
                            'name' => 'generic',
                            'parameters' => array(
                                'ukpostcodes' => 'N15',
                                'BookingAction.original' => 'Book',
                                'Booking-types' => 'Eye-test 16 or over',
                                'Greeting' => 'Hi',
                                'ukpostcodes.original' => 'n15',
                                'Greeting.original' => 'hi',
                                'Booking-types.original' => 'Eye-test 16 or over',
                                'facebook_sender_id' => '1236319853111662',
                                'BookingAction' => 'Book ',
                            ),
                            'lifespan' => 3,
                        ),
                    ),
                    'metadata' => array(
                        'intentId' => '3c6cc178-9499-4731-b1ea-4a67cd136b4e',
                        'webhookUsed' => 'true',
                        'webhookForSlotFillingUsed' => 'false',
                        'intentName' => 'Appointment-find-nearby',
                    ),
                    'fulfillment' => array(
                        'speech' => '',
                        'messages' => array(

                        ),
                    ),
                    'score' => '1.0',
                ),
                'status' => array(
                    'code' => 200,
                    'errorType' => 'success',
                ),
                'sessionId' => '8d7228b4-f448-407c-9459-e74bfbcae0b1',
                'originalRequest' => array(
                    'source' => 'facebook',
                    'data' => array(
                        'sender' => array(
                            'id' => '1236319853111662',
                        ),
                        'recipient' => array(
                            'id' => '100973920424428',
                        ),
                        'message' => array(
                            'mid' => 'mid.1487779844932 =>3274e62181',
                            'text' => 'n15',
                            'seq' => '80095',
                        ),
                        'timestamp' => '1.487779844932E12',
                    ),
                ),
            ));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesXpath('//data');
        $I->seeResponseJsonMatchesXpath('//contextOut');
        $I->seeResponseJsonMatchesXpath('//source');
        $I->seeResponseContainsJson([
            'data' => [
                'facebook' => [
                    'text' => 'The three closest stores to this location are:',
                    'quick_replies' => [
                        [
                            'content_type' => 'text',
                            'title' => 'London - Wood Green',
                            'payload' => 'London - Wood Green',
                        ],
                        [
                            'content_type' => 'text',
                            'title' => 'London - Crouch End',
                            'payload' => 'London - Crouch End',
                        ],
                        [
                            'content_type' => 'text',
                            'title' => 'London - Walthamstow',
                            'payload' => 'London - Walthamstow',
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Tests the store telephone number endpoint
     *
     * @author Stanislav Proskin <stanislav.proshkin@theuniprogroup.com>
     * @param ApiTester $I
     */
    public function storeTelephoneNumber(ApiTester $I)
    {
        $I->wantTo('get Specsavers store phone number');
        $I->haveHttpHeader('Content-Type', 'application/json');
        // see docs at docs.api.ai/docs/webhook for request and response params
        $I->sendPOST(
            'index.php/api/v1/webhook', array(
                'id' => 'c2a57177-bfad-4f58-b2e0-f3d4c30e33ac',
                'timestamp' => '2017-02-27T12:00:58.934Z',
                'lang' => 'en',
                'result' => array(
                    'source' => 'agent',
                    'resolvedQuery' => 'Telephone number',
                    'speech' => '',
                    'action' => 'store-telephone-number',
                    'actionIncomplete' => false,
                    'parameters' => array(

                    ),
                    'contexts' => array(
                        array(
                            'name' => 'storedetail',
                            'parameters' => array(
                                'ukpostcodes' => 'N15',
                                'Greeting' => 'Hi',
                                'ukpostcodes.original' => 'N15',
                                'Greeting.original' => 'Hi',
                                'Storelist' => 'London - Walthamstow',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 3,
                        ),
                        array(
                            'name' => 'opening-hours',
                            'parameters' => array(
                                'Storelist' => 'London - Walthamstow',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 4,
                        ),
                        array(
                            'name' => 'telephone-number',
                            'parameters' => array(
                                'Storelist' => 'London - Walthamstow',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 5,
                        ),
                        array(
                            'name' => 'greeting',
                            'parameters' => array(
                                'ukpostcodes' => 'N15',
                                'Greeting' => 'Hi',
                                'ukpostcodes.original' => 'N15',
                                'Greeting.original' => 'Hi',
                                'Storelist' => 'London - Walthamstow',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 0,
                        ),
                        array(
                            'name' => 'store-find-nearby',
                            'parameters' => array(
                                'ukpostcodes' => 'N15',
                                'Greeting' => 'Hi',
                                'ukpostcodes.original' => 'N15',
                                'Greeting.original' => 'Hi',
                                'Storelist' => 'London - Walthamstow',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 4,
                        ),
                        array(
                            'name' => 'store',
                            'parameters' => array(
                                'ukpostcodes' => 'N15',
                                'Greeting' => 'Hi',
                                'ukpostcodes.original' => 'N15',
                                'Greeting.original' => 'Hi',
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
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 13,
                        ),
                        array(
                            'name' => 'generic',
                            'parameters' => array(
                                'Storelist' => 'London - Walthamstow',
                                'facebook_sender_id' => '1236654559775795',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 4,
                        ),
                    ),
                    'metadata' => array(
                        'intentId' => 'a649fd92-03cb-406d-a3d3-38a34d2ec20b',
                        'webhookUsed' => 'true',
                        'webhookForSlotFillingUsed' => 'false',
                        'intentName' => 'Storeinfo-telephone-number',
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
                            'mid' => 'mid.1488196858722:7919f7af64',
                            'text' => 'Telephone number',
                            'quick_reply' => array(
                                'payload' => 'Telephone number',
                            ),
                            'seq' => 837,
                        ),
                        'timestamp' => 1.488196858722E12,
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
        $I->seeResponseContainsJson([
            'speech' => 'The number for London - Walthamstow is 020 8520 7200.',
            'displayText' => 'The number for London - Walthamstow is 020 8520 7200.',
        ]);
    }

    /**
     * Tests the store opening hours endpoint
     *
     * @author Stanislav Proskin <stanislav.proshkin@theuniprogroup.com>
     * @param ApiTester $I
     */
    public function storeOpeningHours(ApiTester $I)
    {
        $I->wantTo('get Specsavers store opening hours');
        $I->haveHttpHeader('Content-Type', 'application/json');
        // see docs at docs.api.ai/docs/webhook for request and response params
        $I->sendPOST(
            'index.php/api/v1/webhook', array(
                'id' => 'c4cd366d-58b8-4c17-bdd0-9620d7d8e682',
                'timestamp' => '2017-02-27T11:59:44.364Z',
                'lang' => 'en',
                'result' => array(
                    'source' => 'agent',
                    'resolvedQuery' => 'Opening hours',
                    'speech' => '',
                    'action' => 'store-opening-hours',
                    'actionIncomplete' => false,
                    'parameters' => array(

                    ),
                    'contexts' => array(
                        array(
                            'name' => 'booking',
                            'parameters' => array(
                                'ukpostcodes' => 'N15',
                                'ukpostcodes.original' => 'N15',
                                'Storelist' => 'London - Walthamstow',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 1,
                        ),
                        array(
                            'name' => 'opening-hours',
                            'parameters' => array(
                                'Storelist' => 'London - Walthamstow',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 5,
                        ),
                        array(
                            'name' => 'storedetail',
                            'parameters' => array(
                                'ukpostcodes' => 'N15',
                                'ukpostcodes.original' => 'N15',
                                'Storelist' => 'London - Walthamstow',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 3,
                        ),
                        array(
                            'name' => 'telephone-number',
                            'parameters' => array(
                                'Storelist' => 'London - Walthamstow',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 4,
                        ),
                        array(
                            'name' => 'greeting',
                            'parameters' => array(
                                'ukpostcodes' => 'N15',
                                'Greeting' => 'Hi',
                                'ukpostcodes.original' => 'N15',
                                'Greeting.original' => 'hi',
                                'Storelist' => 'London - Walthamstow',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 2,
                        ),
                        array(
                            'name' => 'store-find-nearby',
                            'parameters' => array(
                                'ukpostcodes' => 'N15',
                                'ukpostcodes.original' => 'N15',
                                'Storelist' => 'London - Walthamstow',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 4,
                        ),
                        array(
                            'name' => 'store',
                            'parameters' => array(
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
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 13,
                        ),
                        array(
                            'name' => 'store-information',
                            'parameters' => array(
                                'ukpostcodes' => 'N15',
                                'ukpostcodes.original' => 'N15',
                                'Storelist' => 'London - Walthamstow',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 1,
                        ),
                        array(
                            'name' => 'generic',
                            'parameters' => array(
                                'ukpostcodes' => 'N15',
                                'Greeting' => 'Hi',
                                'ukpostcodes.original' => 'N15',
                                'Greeting.original' => 'hi',
                                'Storelist' => 'London - Walthamstow',
                                'facebook_sender_id' => '1236654559775795',
                                'Storelist.original' => 'London - Walthamstow',
                            ),
                            'lifespan' => 3,
                        ),
                    ),
                    'metadata' => array(
                        'intentId' => 'f00e9754-a9a6-498d-8a13-4331d5994e1c',
                        'webhookUsed' => 'true',
                        'webhookForSlotFillingUsed' => 'false',
                        'intentName' => 'Storeinfo-opening-hours',
                    ),
                    'fulfillment' => array(
                        'speech' => '(Store Name JSON) is open (Opening Hours JSON).',
                        'messages' => array(
                            array(
                                'type' => 0,
                                'speech' => '(Store Name JSON) is open (Opening Hours JSON).',
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
                            'mid' => 'mid.1488196784047:62f9a57d77',
                            'text' => 'Opening hours',
                            'quick_reply' => array(
                                'payload' => 'Opening hours',
                            ),
                            'seq' => 797,
                        ),
                        'timestamp' => 1.488196784047E12,
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
        $I->seeResponseContainsJson([
            'speech' => 'London - Walthamstow is open from 09:00 to 18:00 Monday to Friday; from 09:00 to 17:30 on Saturday; from 10:00 to 17:00 on Sunday.',
            'displayText' => 'London - Walthamstow is open from 09:00 to 18:00 Monday to Friday; from 09:00 to 17:30 on Saturday; from 10:00 to 17:00 on Sunday.',
        ]);
    }
}
