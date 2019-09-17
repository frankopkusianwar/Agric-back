<?php

namespace App\Services;

class GoogleClient
{
    public function __construct()
    {
    }

    /**
     * Initializes an Analytics Reporting API V4 service object.
     *
     * @return object authorized Analytics Reporting API V4 service object.
     */
    public static function initializeAnalytics()
    {
        // Use the developers console and download your service account
        // credentials in JSON format. Place them in this directory or
        // change the key file location if necessary.
        $KEY_FILE_LOCATION = __DIR__ . '/service-account-credentials.json';

        // Create and configure a new client object.
        $client = new \Google_Client();
        $client->setApplicationName("EzyAgric Analytics Reporting");
        $client->setAuthConfig($KEY_FILE_LOCATION);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $analytics = new \Google_Service_Analytics($client); // @phan-suppress-current-line PhanUndeclaredClassMethod
        return $analytics;
    }
}
