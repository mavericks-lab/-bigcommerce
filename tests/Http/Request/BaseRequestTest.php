<?php
declare(strict_types = 1);
namespace Maverickslab\BigCommerce\Test\Http\Request;

use PHPUnit\Framework\TestCase;
use Maverickslab\BigCommerce\Http\Client\BigCommerceHttpClient;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;

class BaseRequestTest extends TestCase
{

    protected $responseHeaders = [
        'Content-Type' => 'application/json'
    ];

    /**
     *
     * @var BigCommerceHttpClient
     */
    protected $httpClient;

    /**
     *
     * @var MockHandler
     */
    protected $mockHandler;

    /**
     *
     * {@inheritdoc}
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    public function setUp()
    {
        $this->mockHandler = new MockHandler();
        
        $handler = HandlerStack::create($this->mockHandler);
        
        $config = array(
            'handler' => $handler
        );
        
        $authToken = 'nk9z9279j1sjnmvj2q1ta1mgbpohq1y';
        $clientId = 'mfv9ywa1a2id6z3c5xf8tmvzd8igdjr';
        $storeId = '76w0mokpa9';
        $clientSecret = '';
        
        $this->httpClient = new BigCommerceHttpClient($authToken, $clientId, $clientSecret, $storeId, $config);
    }

    /**
     *
     * {@inheritdoc}
     * @see \PHPUnit\Framework\TestCase::tearDown()
     */
    public function tearDown()
    {
        $this->httpClient = null;
    }

    public function testClientSetUp()
    {
        $this->assertInstanceOf(BigCommerceHttpClient::class, $this->httpClient);
    }

    /**
     * Asserts that a given response is/was successful
     *
     * @param Response $response
     * @param string $message
     */
    public function assertResponseOk(Response $response = null, string $message = '')
    {
        $this->assertInstanceOf(Response::class, $response);
        
        $this->assertEquals(200, $response->getStatusCode(), $message);
    }

    /**
     * Decodes json content of a given response
     *
     * @param Response $response
     * @return \stdClass|NULL
     */
    protected function responseToJson(Response $response = null): ?\stdClass
    {
        $json = null;
        
        if (null !== $response) {
            $json = json_decode($response->getBody()->getContents());
        }
        
        return $json;
    }

    /**
     *
     * @param string $file
     * @return string
     */
    protected function readResponseFile(string $file): string
    {
        return file_get_contents(dirname(__DIR__) . '/responses/' . $file);
    }
}