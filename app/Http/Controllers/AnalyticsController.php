<?php

namespace App\Http\Controllers;

use App\Utils\Helpers;
use App\Services\GoogleClient;
use Laravel\Lumen\Routing\Controller as BaseController;

class AnalyticsController extends BaseController
{
    private $profileId;
    protected $client;
  
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->profileId = getenv('GOOGLE_ANALYTICS_PROFILE_ID');
        $this->client= GoogleClient::initializeAnalytics();
    }

    /**
   * get the number of visitors
   *
   */
    public function getNumberOfVistors()
    {
        $resultObject = $this->client->data_ga->get(
            'ga:' . $this->profileId,
            '7daysAgo',
            'today',
            'ga:users'
        );
        $visitors_array = $resultObject->getRows();
        $visitors = $visitors_array[0][0];
        return Helpers::returnSuccess(200, [
        'visitorsCount' => $visitors,
      ], "");
    }
}
