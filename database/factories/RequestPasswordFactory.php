<?php

namespace database\factories;

use Faker\Generator as Faker;

class RequestPasswordFactory
{
    public static function getFactory(Faker $faker)
    {
        return [
            '_id' => $faker->uuid,
            'eloquent_type' => 'request-password',
            'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsdW1lbi1qd3QiLCJzdWIiOnsiX2lkIjoidUJ3Z1BxLSIsImVtYWlsIjoiYWRtaW4yMDIwQGdtYWlsLmNvbSJ9LCJpYXQiOjE1NjE1NTAzNzgsImV4cCI6MTU2MjE1NTE3OH0.P0PpyxqeCWDujyF10FKZ5aQz56kWWbkYKpUHAcv8lqE',
            'type' => 'request-password',
        ];
    }
}
