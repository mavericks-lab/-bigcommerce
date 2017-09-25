<?php
namespace Maverickslab\BigCommerce\Test\Http\Request;

use Maverickslab\BigCommerce\Http\Request\MerchantRequest;
use GuzzleHttp\Psr7\Response;

/**
 *
 * @author cosman
 *        
 */
class MerchantRequestTest extends BaseRequestTest
{

    /**
     *
     * @var MerchantRequest
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
        
        $this->request = new MerchantRequest($this->httpClient);
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

    public function testFetchDetails()
    {
        $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('merchant/details.json')));
        
        $response = $this->request->fetchDetails()->wait();
        
        $this->assertResponseOk($response);
    }
}