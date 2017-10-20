<?php
/**
 * Store service API service
 *
 * PHP version 5.6
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category  Specsavers_API.AI_Bot_Web_Services
 * @package   Specsavers_API.AI_Bot_Web_Services
 * @author    Joe Savage <joe.savage@theuniprogroup.com>
 * @author    Stanislav Proshkin <stanislav.proshkin@theuniprogroup.com>
 * @copyright 2017 The Unipro Group
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 */
namespace App\Services;

use GoogleMapsGeocoder;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * The stores service class.
 *
 * @author Joe Savage <joe.savage@theuniprogroup.com>
 */
class StoresService
{

    /**
     * Scrape the Specsavers brand site to return your nearest Specsavers store.
     *
     * Page scraping is used to get Specsavers store data. The Google Geocoding service is used to fetch lat, long coordinates for addresses.

     * @author Joe Savage <joe.savage@theuniprogroup.com>
     * @author Stanislav Proshkin <stanislav.proshkin@theuniprogroup.com>
     * @param string $address
     * @return array
     */
    public function findNearby($address = "")
    {
        // do a latitude and longitude lookup on the address
        $geocoder = new \GoogleMapsGeocoder($address);
        $geocoder->setRegion("uk");
        $geocoderResponse = $geocoder->geocode();
        $countLocationsFound = count($geocoderResponse['results']);

        // set common response vars
        $response = array(
            'source' => 'facebook-ss-sas-bot-web-services',
        );

        // location is invalid so the user will need to provide another location
        if ($countLocationsFound == 0) {
            $response['speech'] = "The location you have provided is invalid. Please specify another location.";
            $response['displayText'] = $response['speech'];
            return $response;
        } else if ($countLocationsFound == 1) {
            // get the location data from the results
            $location = $geocoderResponse['results'][0];
            $lat = $location['geometry']['location']['lat'];
            $long = $location['geometry']['location']['lng'];
        } else {
            // multiple locations so the user needs to clarify this
            $response['speech'] = "Multiple locations have been found for the location you have provided. Please be more specific.";
            $response['displayText'] = $response['speech'];
            return $response;
        }

        // request the select store page from the select store page
        $requestUrl = "https://www.specsavers.co.uk/stores/select-a-store/" . rawurlencode($address) . "/" . $lat . "," . $long;
        $client = new Client();
        $crawler = $client->request('GET', $requestUrl);

        // no stores nearby for the location provided so ask the user for a new location
        $storesNearby = ($crawler->filter('.store-search-no-results')->count() == 0) ? true : false;
        if ($storesNearby === false) {
            $response['speech'] = "We couldn't find any stores near the location " . $address . ". Please try a different location.";
            $response['displayText'] = $response['speech'];
            return $response;
        }

        $storesCollection = $crawler->filter('.view-select-a-store div.view-content li.views-row')
            ->each(function (Crawler $node, $i) {
                // parsing page for store name, distance, booking and store page links
                $currentStoreElement = $node->filter('div.store-name a')->first();
                $storeNameWithDistance = $currentStoreElement->html();
                $currentStorePageUrl = $currentStoreElement->attr('href');
                $currentStoreDistance = $node->filter('div.store-name a span')->text();
                $currentStoreDistance = str_replace(array('(', ')'), array('', ''), $currentStoreDistance);
                $currentStoreDistance = str_replace("mi", "miles", $currentStoreDistance);

                $cutTo = strpos($storeNameWithDistance, '<span');
                $storeName = trim(substr($storeNameWithDistance, 0, $cutTo));
                // making response array of all data
                return array(
                    'booking_url' => $node->filter('div.views-field-link-store-booking a')->first()->attr('href'),
                    'store_url' => $currentStorePageUrl,
                    'name' => $storeName,
                    'distance' => $currentStoreDistance,
                );
            });

        // pulling only 3 results
        $slicedStoresCollection = array_slice($storesCollection, 0, 3);

        // preparing data for facebook messenger
        $response['data'] = array(
            'facebook' => array(
                'text' => 'The three closest stores to this location are:',
                'quick_replies' => array(),
            ),
        );

        // making quick replys buttons
        $indexedStoresCollection = array();
        foreach ($slicedStoresCollection as &$value) {
            // $crawler = $client->request('GET', 'https://www.specsavers.co.uk' . $value['booking_url']);
            // $value['store_id'] = $storeId = $crawler->filter('#appointment-type--section')->attr('data-store-id');
            // $indexedStoresCollection[$storeId] = $value;
            $response['data']['facebook']['quick_replies'][] = array(
                'content_type' => 'text',
                'title' => $value['name'],
                'payload' => $value['name'],
            );
        }

        // Returns a list of all results with links and ids to store it in API.AI contexts and use in future searches

        $response['contextOut'][] = (object) array(
            'name' => 'store',
            'lifespan' => 15,
            'parameters' => array(
                'stores_collection' => $slicedStoresCollection,
            ),
        );

        return $response;
    }

    /**
     * Query the Specsavers brand site to fetch selected store phone number.
     *
     * @author Stanislav Proshkin <stanislav.proshkin@theuniprogroup.com>
     * @param array $storeInformation
     * @return array
     */
    public function storeTelephoneNumber($storeInformation = array())
    {
        // set common response vars
        $response = array(
            'source' => 'facebook-ss-sas-bot-web-services',
            'data' => new \stdClass,
            'contextOut' => array(),
            'speech' => "We are unable to fetch store information at the moment. Please try again later.",
        );
        $response['displayText'] = $response['speech'];

        // checking storeInformation array
        if (empty($storeInformation) || !isset($storeInformation['store_url'])) {
            return $response;
        }

        // request the chosen store page
        $requestUrl = 'https://www.specsavers.co.uk' . $storeInformation['store_url'];

        $client = new Client();
        $crawler = $client->request('GET', $requestUrl);
        $clientResponse = $client->getResponse();

        // checking response status to make sure store page exists
        if ($clientResponse->getStatus() !== 200) {
            return $response;
        }

        // get and parse the store phone number
        $storeTelephoneElement = $crawler->filter('.field-name-field-store-phone span.field-item a')->first();
        $storeTelephoneHtml = $storeTelephoneElement->html();
        $storeTelephoneText = strip_tags($storeTelephoneHtml);

        // return the response with the store phone
        $response['speech'] = 'The number for ' . $storeInformation['name'] . ' is ' . $storeTelephoneText . '.';
        $response['displayText'] = $response['speech'];
        return $response;
    }

    /**
     * Query the Specsavers brand site to fetch selected store manager name.
     *
     * @author Stanislav Proshkin <stanislav.proshkin@theuniprogroup.com>
     * @param array $storeInformation
     * @return array
     */
    public function storeManagerName($storeInformation = array())
    {
        // set common response vars
        $response = array(
            'source' => 'facebook-ss-sas-bot-web-services',
            'data' => new \stdClass,
            'contextOut' => array(),
            'speech' => "We are unable to fetch store information at the moment. Please try again later.",
        );
        $response['displayText'] = $response['speech'];

        // checking storeInformation array
        if (empty($storeInformation) || !isset($storeInformation['store_url'])) {
            return $response;
        }

        // request the chosen store page
        $requestUrl = 'https://www.specsavers.co.uk' . $storeInformation['store_url'];

        $client = new Client();
        $crawler = $client->request('GET', $requestUrl);
        $clientResponse = $client->getResponse();

        // checking response status to make sure store page exists
        if ($clientResponse->getStatus() !== 200) {
            return $response;
        }

        // get and parse the store manager name
        $storeManagerElement = $crawler->filter('div.node-store-staff-profile div.field-name-title')->first();
        $storeManagerText = trim($storeManagerElement->text());

        // return the response with the store phone
        $response['speech'] = 'The Manager Name for ' . $storeInformation['name'] . ' is ' . $storeManagerText . '.';
        $response['displayText'] = $response['speech'];
        return $response;
    }

    /**
     * Query the Specsavers brand site to fetch selected store opening hours.
     *
     * @author Stanislav Proshkin <stanislav.proshkin@theuniprogroup.com>
     * @param array $storeInformation
     * @return array
     */
    public function storeOpeningHours($storeInformation = array())
    {
        // set common response vars
        $response = array(
            'source' => 'facebook-ss-sas-bot-web-services',
            'data' => new \stdClass,
            'contextOut' => array(),
            'speech' => "We are unable to fetch store information at the moment. Please try again later.",
        );
        $response['displayText'] = $response['speech'];

        // checking storeInformation array
        if (empty($storeInformation) || !isset($storeInformation['store_url'])) {
            return $response;
        }

        // request the chosen store page
        $requestUrl = 'https://www.specsavers.co.uk' . $storeInformation['store_url'];

        $client = new Client();
        $crawler = $client->request('GET', $requestUrl);
        $clientResponse = $client->getResponse();

        // checking response status to make sure store page exists
        if ($clientResponse->getStatus() !== 200) {
            return $response;
        }

        // get and parse the store phone number
        $storeOpeningHoursCollection = $crawler
            ->filter('div.field-name-field-opening-times div.field-items div.field-item span.oh-display')
            ->each(function (Crawler $node, $i) {
                return array(
                    'day' => $node->filter('span.oh-display-label')->first()->text(),
                    'time' => $node->filter('span.oh-display-times')->first()->text(),
                );
            });

        // making short form of working time frames e.g. monday-friday 9:00-18:00
        $numberOfDays = sizeof($storeOpeningHoursCollection);
        $openingMap = $storeOpeningHours = array();

        // start with current day
        for ($i = 0; $i < $numberOfDays; $i++) {
            $temp = array($i);
            // try to find matching adjacent days
            for ($j = $i + 1; $j < $numberOfDays; $j++) {
                if ($storeOpeningHoursCollection[$i]['time'] == $storeOpeningHoursCollection[$j]['time']) {
                    // we have a match, store the day
                    $temp[] = $j;
                    if ($j == $numberOfDays - 1) {
                        $i = $numberOfDays - 1; // edge case
                    }
                } else {
                    // otherwise, move on to the next day
                    $i = $j - 1;
                    $j = $numberOfDays; // break
                }
            }
            $openingMap[] = $temp; // $temp will be an array of matching days (possibly only 1 day)
        }

        foreach ($openingMap as $daysRange) {
            if ($storeOpeningHoursCollection[$daysRange[0]]['time'] == 'Closed') {
                continue;
            }
            if (sizeof($daysRange) > 1) {
                $lastElement = end($daysRange);
                $storeOpeningHours[] =
                'from ' . str_replace('-', ' to ', $storeOpeningHoursCollection[$daysRange[0]]['time']) .
                ' ' . trim(str_replace(':', '', $storeOpeningHoursCollection[$daysRange[0]]['day'])) .
                ' to ' . trim(str_replace(':', '', $storeOpeningHoursCollection[$lastElement]['day']));
            } else {
                $storeOpeningHours[] =
                'from ' . str_replace('-', ' to ', $storeOpeningHoursCollection[$daysRange[0]]['time']) .
                ' on ' . trim(str_replace(':', '', $storeOpeningHoursCollection[$daysRange[0]]['day']));
            }
        }

        $storeOpeningHoursText = implode('; ', $storeOpeningHours);

        // return the response with the store opening hours
        $response['speech'] = $storeInformation['name'] . ' is open ' . $storeOpeningHoursText . '.';
        $response['displayText'] = $response['speech'];
        return $response;
    }

}
