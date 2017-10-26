<?php
declare(strict_types = 1);
namespace Maverickslab\BigCommerce\Http\Request;

use GuzzleHttp\Promise\PromiseInterface;
use Maverickslab\BigCommerce\Exception\BigCommerceException;

/**
 *
 * @author cosman
 *        
 */
class CategoryRequest extends BaseRequest
{

    /**
     * Generates request to count number of categories
     *
     * @return PromiseInterface
     */
    public function count(): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->getAsync('categories/count');
    }

    /**
     * Generates a request to create a new category
     *
     * @param array $category
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function create(array $category): PromiseInterface
    {
        return $this->httpClient->postAsync('catalog/categories', array(
            'json' => $category
        ));
    }

    /**
     * Generates request to create at least one category
     *
     * @param array ...$categories
     * @return PromiseInterface
     */
    public function createCategories(array ...$categories): PromiseInterface
    {
        $promises = array_map(function ($category) {
            return $this->create($category);
        }, $categories);
        
        return $this->httpClient->batchExecute($promises);
    }

    /**
     * Generates request to update an existing category
     *
     * @param int $categoryId
     * @param array $categoryInfo
     * @return PromiseInterface
     */
    public function update($categoryId, array $categoryInfo): PromiseInterface
    {
        return $this->httpClient->putAsync(sprintf('catalog/categories/%d', $categoryId), array(
            'json' => $categoryInfo
        ));
    }

    /**
     * Generates request to fetch a number of categories
     *
     * @param number $page
     * @param number $limit
     * @param array $filters
     *            Additional filtering parameters
     * @return PromiseInterface
     */
    public function fetch($page = 1, $limit = 50, array $filters = []): PromiseInterface
    {
        $queries = array_merge_recursive($filters, array(
            'page' => $page,
            'limit' => $limit
        ));
        
        return $this->httpClient->getAsync('catalog/categories', array(
            'query' => $queries
        ));
    }

    /**
     * Generates request to fetch a single category by id
     *
     * @param int $id
     * @param array $includeFields
     * @param array $excludeFields
     * @return PromiseInterface
     */
    public function fetchById(int $id, array $includeFields = [], array $excludeFields = []): PromiseInterface
    {
        $queries = array();
        
        if (! empty($includeFields)) {
            $queries['include_fields'] = implode(',', $includeFields);
        }
        
        if (! empty($excludeFields)) {
            $queries['exclude_fields'] = implode(', ', $excludeFields);
        }
        
        return $this->httpClient->getAsync(sprintf('catalog/categories/%d', $id), array(
            'query' => $queries
        ));
    }

    /**
     * Generates request to delete categories matching an array of filters
     *
     * @param array $filters
     * @return PromiseInterface
     */
    public function delete(array $filters = []): PromiseInterface
    {
        return $this->httpClient->deleteAsync('catalog/categories', array(
            'query' => $filters
        ));
    }

    /**
     * Generates request to delete a single category by Id
     *
     * @param int $id
     * @return PromiseInterface
     */
    public function deleteById(int $id): PromiseInterface
    {
        return $this->httpClient->deleteAsync(sprintf('catalog/categories/%d', $id));
    }

    /**
     * Generates request to upload a new image for a given category
     *
     * @param int $categoryId
     * @param string $filePath
     * @throws BigCommerceException
     * @return PromiseInterface
     */
    public function createCategoryImage(int $categoryId, string $filePath): PromiseInterface
    {
        $options = array();
        
        if (false !== filter_var($filePath, FILTER_VALIDATE_URL)) {
            $options = array(
                'json' => array(
                    'image_url' => $filePath
                )
            );
        } else {
            
            if (! file_exists($filePath)) {
                throw new BigCommerceException(sprintf('File "%s" does not exist.', $filePath));
            }
            
            if (! is_file($filePath)) {
                throw new BigCommerceException(sprintf('File "%s" is not a valid file.', $filePath));
            }
            
            if (! is_readable($filePath)) {
                throw new BigCommerceException(sprintf('File "%s" is not readable.', $filePath));
            }
            
            $options = array(
                'multipart' => array(
                    array(
                        'name' => 'image_file',
                        'contents' => fopen($filePath, 'r')
                    )
                )
            );
        }
        
        return $this->httpClient->postAsync(sprintf('catalog/categories/%d/image', $categoryId), $options);
    }

    /**
     * Generates request to delete images for a given category
     *
     * @param int $categoryId
     * @throws BigCommerceException
     * @return PromiseInterface
     */
    public function deleteCategoryImages(int $categoryId): PromiseInterface
    {
        return $this->httpClient->postAsync(sprintf('catalog/categories/%d/image', $categoryId));
    }
}