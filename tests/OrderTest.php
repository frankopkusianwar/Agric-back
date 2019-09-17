<?php
use App\Models\Order;
use App\Utils\OrderMockData;
use \Mockery as Mock;

class OrderTest extends TestCase
{
    const COMPLETED_ORDERS_URL = '/api/v1/orders/completed';
    const RECEIVED_ORDERS_URL = '/api/v1/orders/received';
    const URL = '/api/v1/inputs';
    const ORDERS_URL = '/api/v1/orders/';

    protected $token;
    protected $mockedOrderData;

    public function setUp(): void
    {
        parent::setUp();
        $this->mock = new OrderMockData();
        $this->response = $this->call('POST', '/api/v1/auth/login', $this->mock->getLoginDetails());
        $result = json_decode($this->response->getContent());
        $this->token = $result->token;
    }

    public function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

    public function testShouldReturnCompletedOrders()
    {
        $this->get(self::COMPLETED_ORDERS_URL, ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->seeJson(['success' => true]);
        $this->seeJsonStructure([
            'completed_orders' => [],
            'count',
            'success',
        ]);
    }

    public function testRouteRequiresAuthentication()
    {
        $this->get(self::COMPLETED_ORDERS_URL);
        $this->seeStatusCode(401);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => false]);
        $this->seeJson(['error' => 'Please log in first.']);
    }

    public function testShouldReturnTotalOrders()
    {
        $this->get('/api/v1/orders', ['Authorization' => $this->token]);
        $res_array = (array) json_decode($this->response->content());
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
    }

    public function testShouldNotDeleteOrderOfUnknownID()
    {
        $this->delete('/api/v1/orders/fakeID', [], ['Authorization' => $this->token]);
        $this->seeStatusCode(404);
        $this->seeJson(['success' => false]);
    }

    public function testShouldDeleteOrderOfknownID()
    {
        $orderInfo = $this->mock->getOrderData();
        $orderData = Order::create($orderInfo)->getAttributes();
        $this->delete(self::ORDERS_URL . $orderData['_id'], [], ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->seeJson(['success' => true]);
    }

    public function testShouldReturnAllNewOrdersBetweenDateRange()
    {
        $this->get('/api/v1/orders?start_date=2019-08-08 & end_date=2019-08-28', ['Authorization' => $this->token]);
        $res_array = (array) json_decode($this->response->content());
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
    }

    public function testShouldReturnAvailableStock()
    {
        $this->get(self::URL, ['Authorization' => $this->token]);
        $res_array = (array) json_decode($this->response->content());
        $this->seeStatusCode(200);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => true]);
    }

    public function testShouldReturnErrorIfWrongDateInput()
    {
        $this->get('/api/v1/orders?start_date=2019-18-08 & end_date=2019-18-0', ['Authorization' => $this->token]);
        $res_array = (array) json_decode($this->response->content());
        $this->seeStatusCode(503);
        $this->assertEquals('application/json', $this->response->headers->get('Content-Type'));
        $this->seeJson(['success' => false]);
        $this->seeJson(['error' => 'Could not get orders']);
    }

    public function testShouldReturnReceivedOrders()
    {
        $this->get(self::RECEIVED_ORDERS_URL, ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->seeJson(['success' => true]);
        $this->seeJsonStructure([
            'received_orders' => [],
            'count',
            'success',
        ]);
    }

    public function testInvalidRouteParameterReturnsError()
    {
        $this->get('/api/v1/orders/wrong', ['Authorization' => $this->token]);
        $this->seeStatusCode(400);
        $this->seeJson(['success' => false]);
        $this->seeJsonStructure([
            'success',
            'error',
        ]);
    }

    public function testControllerReturnsSuccessWithValidID()
    {
        $this->withoutMiddleware();
        $id = 'asdasd43e-sdcasdca';
        $mock = Mock::mock(Order::class);
        $this->app->instance(Order::class, $mock);
        $mock->shouldReceive(['where' => $mock])->with('_id', $id)->once();
        $mock->shouldReceive(['first' => $mock]);
        $mock->shouldReceive(['getAttribute' => 'New']);
        $mock->shouldReceive(['setAttribute' => 'Intransit']);
        $mock->shouldReceive(['save' => true]);

        $response = $this->call('patch', self::ORDERS_URL . '' . $id . '/new');
    }
    public function testValidOrderCategoryRouteParameterReturnsError()
    {
        $this->get('/api/v1/order-distribution/Fertilizer', ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->seeJson(['success' => true]);
    }

    public function testMarkAsClearedWithInvalidID()
    {
        $this->withoutMiddleware();
        $id = 1;

        $this->patch(self::ORDERS_URL . '' . $id . '/new');

        $this->seeStatusCode(403);
        $this->seeJson(['success' => false]);
    }
    public function testInvalidOrderCategoryRouteParameterReturnsError()
    {
        $this->get('/api/v1/order-distribution/Fertilizers', ['Authorization' => $this->token]);
        $this->seeStatusCode(400);
        $this->seeJson(['success' => false]);
    }
    public function testShouldConvertStringToLower()
    {
        $this->get('/api/v1/order-distribution/Planting', ['Authorization' => $this->token]);
        $this->seeStatusCode(200);
        $this->seeJson(['success' => true]);
    }
}
