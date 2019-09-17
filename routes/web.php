<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->post('/contact', 'ContactController@sendContactForm');
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('/login', 'AuthController@authenticate');
        $router->post('/forgot-password', 'AuthController@forgotPassword');
        $router->post('/password-verification-token', 'AuthController@verifyResetPasswordToken');
        $router->post('/resend-password', 'AuthController@forgotPassword');
        $router->put('/confirm-password', 'AuthController@confirmPassword');
    });
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['middleware' => 'admin'], function () use ($router) {
            $router->group(['middleware' => 'validateParams'], function () use ($router) {
                $router->post('/users/{user}/', 'UserController@createUser');
            });
            $router->get('/active-users', 'UserController@getAllActiveUsers');
            $router->get('/active-mobile-users', 'UserController@getActiveMobileUsers');
            $router->post('/change-password', 'AdminController@changePassword');
            $router->post('/admin', 'AdminController@createAdmin');
            $router->get('/masteragents', 'MasterAgentController@getMasterAgents');
            $router->post('/devt-partners', 'DevtPartnerController@createDevtPartner');
            $router->get('/users/{user}', 'AdminExtendedController@getUsers');
            $router->get('/devt-partners', 'DevtPartnerController@getDevtPartners');
            $router->get('/top-districts', 'DistrictController@getTopDistricts');
            $router->get('/activity-summary', 'ActivityController@getActivitySummary');
            $router->get('/total-acreage', 'MapCordinatesController@getTotalAcreage');
            $router->get('/total-payment', 'TotalPaymentController@getTotalPayment');
            $router->get('/twitter-report', 'ReportController@getTwitterReport');
            $router->get('/youtube-report', 'ReportController@getYoutubeReport');
            $router->get('/facebook-report', 'ReportController@getFacebookReport');
            $router->get('/admins', 'AdminController@getAdmins');
            $router->delete('/account/{id}', 'AdminExtendedController@deleteAccount');
            $router->patch('/account/{id}', 'AdminExtendedController@editAccount');
            $router->get('/top-produce', 'FarmerProduceController@getTopFarmProduce');
            $router->get('/top-performing/{agent}', 'AdminController@getTopAgents');
            $router->get('/top-performing-district', 'DistrictController@getTopPerformingDistricts');
            $router->get('/top-performing-district/{district}', 'DistrictController@getTopPerformingDistricts');
            $router->get('/account/{id}', 'UserController@getUser');
            $router->get('/districts', 'DistrictController@getDistricts');
            $router->get('/enterprises', 'OurCropController@getEnterprises');
            $router->get('/most-ordered', 'ReportController@getMostOrdered');
            $router->get('/farmer-agents-order-statistics', 'ReportController@getFarmerAgentsOrderStatistics');
            $router->patch('/{action}/{id}', 'UserController@accountAction');
            $router->post('/village-agents', 'UserController@addVillageAgents');
            $router->get('/farmers', 'FarmerController@getFarmers');
            $router->get('/visitor', 'AnalyticsController@getNumberOfVistors');
            $router->get('/farmers-orders', 'FarmersOrderController@getNumberOfFarmersWhoOrderedDifferentInputCategories');
            // input routes
            $router->get('/inputs/{id}', 'InputController@getInputDetails');
            $router->put('/inputs/{id}', 'InputController@updateInput');
            $router->post('/inputs', 'InputController@createInput');
            $router->get('/input-list', 'InputController@getInputs');
            $router->delete('/inputs/{id}', 'InputController@deleteInput');
            $router->get('/orders', 'OrderController@getOrders');
            $router->get('/activity-log', 'ActivityController@getActivityLog');
            $router->get("/inputs", 'OrderController@getInputsStock');
            $router->post('/crops', 'OurCropController@addCrop');
            $router->get('/crops/{id}', 'OurCropController@getCrop');
            $router->get('/crops', 'OurCropController@getCrops');
            $router->post('/crops/{id}', 'OurCropController@editCrop');
            $router->delete('/crops/{id}', 'OurCropController@deleteCrop');
            $router->post('agronomical-info/{id}', 'AgronomicalController@updateAgronomicalInfo');
            $router->delete('agronomical-info/{id}', 'AgronomicalController@deleteAgronomicalInfo');
            $router->group(['middleware' => ['validateDiagnosisCategory', 'documentExist']], function () use ($router) {
                $router->post('/diagnosis/{category}', 'DiagnosisController@createDiagnosis');
                $router->get('/diagnosis/{category}', 'DiagnosisController@getDiagnosis');
                $router->get('/diagnosis/{category}/{id}', 'DiagnosisController@getDiagnosis');
                $router->post('/diagnosis/{category}/{id}', 'DiagnosisController@editDiagnosisInformation');
            });
            $router->group(['middleware' => 'documentExist'], function () use ($router) {
                $router->delete('/diagnosis/{id}', 'DiagnosisController@deleteDiagnosis');
                $router->delete('/orders/{id}', 'OrderController@deleteOrderOfId');
            });
            $router->get('/orders/{type}', 'OrderController@getOrdersByType');
            $router->patch('/orders/{id}/new', 'OrderController@markAsCleared');

            $router->group(['middleware' => 'validateOrderCategory'], function () use ($router) {
                $router->get('/order-distribution/{category}', 'OrderController@getOrderDistribution');
            });
        });
    });
    $router->group(['middleware' => 'validateParams'], function () use ($router) {
        $router->post('/request/{user}', 'UserController@requestAccount');
    });
});
