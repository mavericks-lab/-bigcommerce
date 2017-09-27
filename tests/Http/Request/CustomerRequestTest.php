<?php
declare(strict_types = 1);
namespace Maverickslab\BigCommerce\Test\Http\Request;

use GuzzleHttp\Psr7\Response;
use Maverickslab\BigCommerce\Http\Request\CustomerRequest;

class CustomerRequestTest extends BaseRequestTest
{

    /**
     *
     * @var CustomerRequest
     */
    protected $request;

    /**
     *
     * {@inheritdoc}
     * @see \Maverickslab\BigCommerce\Test\Http\Request\BaseRequestTest::setUp()
     */
    public function setUp() 
    {
        parent::setUp();
        
        $this->request = new CustomerRequest($this->httpClient);
    }

    /**
     *
     * {@inheritdoc}
     * @see \Maverickslab\BigCommerce\Test\Http\Request\BaseRequestTest::tearDown()
     */
    public function tearDown()
    {
        $this->request = null;
        
        parent::tearDown();
    }

    public function testFetchSubscribers()
    {
        $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('customer/list.json')));
        
        $response = $this->request->fetch()->wait();
        
        $this->assertResponseOk($response);
        
        $customersData = $this->responseToJson($response);
        
        $this->assertTrue(0 < count($customersData->data));
    }

    public function testFetchSubscriberById()
    {
        $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('customer/fetch-one.json')));
        
        $customerId = 3;
        
        $response = $this->request->fetchById($customerId)->wait();
        
        $this->assertResponseOk($response);
        
        $customerData = $this->responseToJson($response);
        
        $this->assertNotNull($customerData);
        
        $customer = $customerData->data;
        
        $this->assertEquals($customerId, $customer->id);
    }

    public function testCreateSubscriber()
    {
        $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('customer/create.json')));
        
        $customerInfo = array(
            'first_name' => 'Kofi',
            'last_name' => 'Nkansah',
            'email' => 'kofi.nkansah@gmail.com'
        );
        
        $response = $this->request->create($customerInfo)->wait();
        
        $this->assertResponseOk($response);
        
        $customerData = $this->responseToJson($response);
        
        $this->assertNotNull($customerData);
        
        $customer = $customerData->data;
        
        $this->assertEquals($customerInfo['first_name'], $customer->first_name);
        
        $this->assertEquals($customerInfo['last_name'], $customer->last_name);
        
        $this->assertEquals($customerInfo['email'], $customer->email);
    }

    public function testUpdateSubscriber()
    {
        $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('customer/update.json')));
        
        $customerId = 2;
        
        $customerInfo = array(
            'first_name' => 'Kofi',
            'last_name' => 'Nkansah-Manu',
            'email' => 'kofi.nkansh-menu@gmail.com'
        );
        
        $response = $this->request->update($customerId, $customerInfo)->wait();
        
        $this->assertResponseOk($response);
        
        $customerData = $this->responseToJson($response);
        
        $this->assertNotNull($customerData);
        
        $customer = $customerData->data;
        
        $this->assertEquals($customerId, $customer->id);
        
        $this->assertEquals($customerInfo['first_name'], $customer->first_name);
        
        $this->assertEquals($customerInfo['last_name'], $customer->last_name);
        
        $this->assertEquals($customerInfo['email'], $customer->email);
    }
}