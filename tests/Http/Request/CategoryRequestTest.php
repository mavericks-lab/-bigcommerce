<?php
declare(strict_types = 1);
namespace Maverickslab\BigCommerce\Test\Http\Request;

use Maverickslab\BigCommerce\Http\Request\CategoryRequest;
use GuzzleHttp\Psr7\Response;

class CategoryRequestTest extends BaseRequestTest
{

    /**
     *
     * @var CategoryRequest
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
        
        $this->request = new CategoryRequest($this->httpClient);
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
     * Fetches a number of categories
     *
     * @param int $page
     * @param number $limit
     * @return array
     */
    protected function fetchCategories(int $page = 1, $limit = 50): \stdClass
    {
        $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('category/list.json')));
        
        $response = $this->request->fetch($page, $limit)->wait();
        
        return json_decode($response->getBody()->getContents());
    }

    /**
     * Returns total number of categories
     *
     * @return int
     */
    protected function totalCategory(): int
    {
        $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('category/list.json')));
        
        $countResponseData = $this->fetchCategories();
        
        return ! empty($countResponseData->meta->pagination->total) ? (int) $countResponseData->meta->pagination->total : 0;
    }

    public function testFetch()
    {
        $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('category/list.json')));
        
        $response = $this->request->fetch()->wait();
        
        $this->assertResponseOk($response);
    }

    public function testFetchById()
    {
        $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('category/fetch-one.json')));
        
        $response = $this->request->fetchById(1)->wait();
        
        $this->assertResponseOk($response);
        
        $category = json_decode($response->getBody()->getContents());
        
        $this->assertTrue(! empty($category->data->id));
    }

    public function newCategoriesProvider(): array
    {
        return array(
            array(
                array(
                    'name' => 'Category ' . rand(),
                    'parent_id' => 0
                )
            ),
            array(
                array(
                    'name' => 'Category ' . rand(),
                    'parent_id' => 0
                )
            ),
            array(
                array(
                    'name' => 'Category ' . rand(),
                    'parent_id' => 0
                )
            ),
            array(
                array(
                    'name' => 'Category ' . rand(),
                    'parent_id' => 0
                )
            ),
            array(
                array(
                    'name' => 'Category ' . rand(),
                    'parent_id' => 0
                )
            )
        );
    }

    /**
     * @dataProvider newCategoriesProvider
     *
     * @param array $categoryInfo
     */
    public function testCreate(array $categoryInfo)
    {
        $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('category/create.json')));
        
        $initialCounts = $this->totalCategory();
        
        $response = $this->request->create($categoryInfo)->wait();
        
        $this->assertResponseOk($response);
        
        $newCounts = $this->totalCategory();
        
        $this->assertTrue($initialCounts < $newCounts);
    }

    public function testCreateManyCategories()
    {
        $newCategories = array_map(function ($cat) {
            $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('category/create.json')));
            
            return $cat[0];
        }, $this->newCategoriesProvider());
        
        $responseArray = $this->request->createCategories(...$newCategories)->wait();
        
        $this->assertTrue(is_array($responseArray));
        
        $this->assertEquals(count($newCategories), count($responseArray));
        
        foreach ($responseArray as $response) {
            $this->assertResponseOk($response);
        }
    }

    public function testUpdateCategory()
    {
        $categories = $this->fetchCategories()->data;
        
        $this->assertTrue(is_array($categories));
        
        $this->assertTrue(0 < count($categories));
        
        $promises = array_map(function ($category) {
            $this->mockHandler->append(new Response(200, $this->responseHeaders, $this->readResponseFile('category/update.json')));
            
            return $this->request->update($category->id, array(
                'name' => sprintf('%s %d', $category->name, rand())
            ));
        }, $categories);
        
        $responses = $this->request->resolvePromises($promises)->wait();
        
        $this->assertTrue(is_array($responses));
        
        $this->assertEquals(count($categories), count($responses));
        
        foreach ($responses as $response) {
            $this->assertResponseOk($response);
        }
    }
}