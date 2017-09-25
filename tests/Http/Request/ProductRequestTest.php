<?php
declare(strict_types = 1);
namespace Maverickslab\BigCommerce\Test\Http\Request;

use Maverickslab\BigCommerce\Http\Request\ProductRequest;
use Exception;
use GuzzleHttp\Psr7\Response;

class ProductRequestTest extends BaseRequestTest
{

    protected static $newProduct;

    /**
     *
     * @var ProductRequest
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
        
        $this->request = new ProductRequest($this->httpClient);
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

    /**
     * Tests product creation
     * @expectedException GuzzleHttp\Exception\ClientException
     */
    public function testCreateEmptyProduct()
    {
        $this->mockHandler->append(new Response(422, $this->responseHeaders));
        
        $product = array();
        
        $this->request->create($product)->wait();
    }

    public function productsDataProvider()
    {
        $this->setUp();
        
        try {
            $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('product/list.json')));
            
            $response = $this->request->fetch()->wait();
            
            $reponseData = json_decode($response->getBody()->getContents());
            $collection = $reponseData->data;
            return array_map(function ($product) {
                return [
                    $product
                ];
            }, $reponseData->data);
        } catch (Exception $e) {
            var_dump($e);
            return [];
        }
    }

    public function createProductDataProvider()
    {
        return array(
            array(
                array(
                    'name' => 'Test Product ' . rand(),
                    'price' => 1505.4,
                    'categories' => array(
                        18
                    ),
                    'weight' => 5,
                    'type' => 'physical'
                )
            ),
            array(
                array(
                    'name' => 'Test Product ' . rand(),
                    'price' => 15.54,
                    'categories' => array(
                        18
                    ),
                    'weight' => 6,
                    'type' => 'digital'
                )
            ),
            array(
                array(
                    'name' => 'Test Product' . rand(),
                    'price' => 10.54,
                    'categories' => array(
                        18
                    ),
                    'weight' => 56,
                    'type' => 'digital'
                )
            ),
            array(
                array(
                    'name' => 'Test Product ' . rand(),
                    'price' => 50.54,
                    'categories' => array(
                        18
                    ),
                    'weight' => 0.6,
                    'type' => 'physical'
                )
            )
        );
    }

    /**
     * Tests product creation
     * @dataProvider createProductDataProvider
     */
    public function testCreate($product)
    {
        $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('product/create.json')));
        
        $response = $this->request->create($product)->wait();
        
        $this->assertResponseOk($response);
    }

    public function testCreateMany()
    {
        $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('product/create.json')));
        $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('product/create.json')));
        $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('product/create.json')));
        $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('product/create.json')));
        
        $products = array(
            array(
                'name' => 'Test Product ' . rand(),
                'price' => 1505.4,
                'categories' => array(
                    18
                ),
                'weight' => 5,
                'type' => 'physical'
            ),
            array(
                'name' => 'Test Product ' . rand(),
                'price' => 15.54,
                'categories' => array(
                    18
                ),
                'weight' => 6,
                'type' => 'digital'
            ),
            array(
                'name' => 'Test Product' . rand(),
                'price' => 10.54,
                'categories' => array(
                    18
                ),
                'weight' => 56,
                'type' => 'digital'
            ),
            array(
                'name' => 'Test Product ' . rand(),
                'price' => 50.54,
                'categories' => array(
                    18
                ),
                'weight' => 0.6,
                'type' => 'physical'
            )
        );
        
        $responses = $this->request->createMany(...$products)->wait();
        
        $this->assertTrue(is_array($responses));
        
        foreach ($responses as $response) {
            $this->assertResponseOk($response);
        }
    }

    /**
     * Tests product update
     * @dataProvider productsDataProvider
     */
    public function testUpdate($product)
    {
        $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('product/update.json')));
        
        // $this->assertInstanceOf(\stdClass::class, $product);
        
        $updateInfo = array(
            'name' => 'Updated product name'
        );
        
        $response = $this->request->update($product->id, $updateInfo)->wait();
        
        $this->assertResponseOk($response);
    }

    public function testFetch()
    {
        $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('product/list.json')));
        
        $response = $this->request->fetch()->wait();
        
        $this->assertResponseOk($response);
    }

    public function testFetchVariants()
    {
        $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('product/variant/list.json')));
        
        $productId = 3;
        
        $response = $this->request->fetchProductVariants($productId)->wait();
        
        $this->assertResponseOk($response);
    }

    public function testCreateVarient()
    {
        $productId = 3;
        
        $variations = array(
            array(
                "sku" => "TOSHIBA-HDD-XD5",
                "price" => 350,
                "weight" => 3.5,
                "option_values" => array(
                    array(
                        "id" => 22,
                        "option_id" => 18
                    ),
                    array(
                        "id" => 18,
                        "option_id" => 17
                    
                    )
                )
            ),
            array(
                "sku" => "TOSHIBA-HDD-XD4",
                "price" => 352.50,
                "weight" => 3.5,
                "option_values" => array(
                    array(
                        "id" => 22,
                        "option_id" => 18
                    ),
                    array(
                        "id" => 17,
                        "option_id" => 17
                    
                    )
                )
            )
        );
        
        foreach ($variations as $variant) {
            $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('product/variant/create.json')));
        }
        
        $responses = $this->request->createProductVariants($productId, ...$variations)->wait();
        
        $this->assertTrue(is_array($responses));
        
        foreach ($responses as $response) {
            $this->assertResponseOk($response);
        }
    }
}