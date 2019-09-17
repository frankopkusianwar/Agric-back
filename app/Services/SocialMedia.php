<?php
namespace App\Services;

use Abraham\TwitterOAuth\TwitterOAuth;
use Exception;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use GuzzleHttp\Client;
use \Facebook\Facebook;

/*
 * @package SocialMedia
 */

class SocialMedia
{
    /**
     * Returns the number of twitter followers and tweets
     */
    public static function getTwitterSummary()
    {
        $twitterConnection = new TwitterOAuth(env('TWITTER_API_KEY'), env('TWITTER_API_KEY_SECRET'), env('TWITTER_ACCESS_TOKEN'), env('TWITTER_ACCESS_TOKEN_SECRET'));
        $user_timeline = $twitterConnection->get('statuses/user_timeline', ['count' => 1]);
        if ($twitterConnection->getLastHttpCode() !== 200) {
            throw new \Exception('Some error occurred while connecting to the Twitter API');
        }
        $userObject = $user_timeline[0]->user;
        return [
            'followers_count' => $userObject->followers_count,
            'statuses_count' => $userObject->statuses_count,
        ];
    }

    /**
     * Returns the number of youtube channel subscription and views
     */
    public static function getYoutubeSummary()
    {
        $channel_id = env('YB_CHANNEL_ID');
        $key = env('YB_API_KEY');
        $guzzle_client = new Client();
        $result = $guzzle_client->request('GET', "https://www.googleapis.com/youtube/v3/channels?part=statistics&id=$channel_id&key=$key");
        if ($result->getStatusCode() !== 200) {
            throw new Exception('Error connecting to Youtube client');
        }
        return json_decode($result->getBody());
    }

    /**
     * Returns the number of facebook page likes and shares
     */
    public static function getFacebookSummary()
    {
        try {
            $fb = new Facebook([
                'app_id' => env('FB_APP_ID'),
                'app_secret' => env('FB_APP_SECRET'),
                'default_graph_version' => 'v2.10',
            ]);
            $response = $fb->get('/' . env('FB_PAGE_ID') . '?fields=posts,fan_count', env('FB_ACCESS_TOKEN'));
        } catch (FacebookResponseException $e) {
            throw new Exception('Error connecting to Facebook');
        } catch (FacebookSDKException $e) {
            throw new Exception('Error connecting to Facebook');
        }
        ['posts' => ['data' => $posts], 'fan_count' => $fanCount] = $response->getDecodedBody();
        $shares = 0;
        foreach ($posts as $post) {
            $postResponse = $fb->get('/' . $post['id'] . '?fields=shares', env('FB_ACCESS_TOKEN'));
            $share_count = $postResponse->getDecodedBody();
            if (array_key_exists('shares', $share_count)) {
                $shares += $share_count['shares']['count'];
            }
        }
        return ['fanCount' => $fanCount, 'shares' => $shares];
    }
}
