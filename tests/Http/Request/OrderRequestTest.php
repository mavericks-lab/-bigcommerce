<?php
declare(strict_types = 1);
namespace Maverickslab\BigCommerce\Test\Http\Request;

use Maverickslab\BigCommerce\Http\Request\OrderRequest;

/**
 *
 * @author cosman
 *        
 */
class OrderRequestTest extends BaseRequestTest
{

    /**
     *
     * @var OrderRequest
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
        
        $this->request = new OrderRequest($this->httpClient);
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

    public function testFetch()
    {
        $this->assertTrue(true);
    }
}