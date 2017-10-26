<?php
namespace Maverickslab\BigCommerce\Http\Request;

use GuzzleHttp\Promise\PromiseInterface;
use Maverickslab\BigCommerce\Exception\BigCommerceException;

/**
 * Class for managing product requests
 *
 * @author cosman
 *        
 */
class ProductRequest extends BaseRequest
{

    /**
     * Generates request to count number of products
     * 
     * @return PromiseInterface
     */
    public function count(): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->getAsync('products/count');
    }

    /**
     * Generates request to create a new product
     *
     * @param array $product
     * @return PromiseInterface
     */
    public function create(array $product): PromiseInterface
    {
        return $this->httpClient->postAsync('catalog/products', array(
            'json' => $product
        ));
    }

    /**
     * Generates request to create at least one product
     *
     * @param array ...$products
     * @return PromiseInterface
     */
    public function createMany(array ...$products): PromiseInterface
    {
        $promises = array_map(function ($product) {
            return $this->create($product);
        }, $products);
        
        return $this->httpClient->batchExecute($promises);
    }

    /**
     * Updates an existing product
     *
     * @param int $productId
     * @param array $productInfo
     * @return PromiseInterface
     */
    public function update(int $productId, array $productInfo): PromiseInterface
    {
        return $this->httpClient->putAsync(sprintf('catalog/products/%d', $productId), array(
            'json' => $productInfo
        ));
    }

    /**
     * Generates request to fetch a number of products from the current store
     *
     * @param int $page
     * @param int $limit
     * @param string[] $includes
     * @param array $includeFields
     * @param array $excludeFields
     * @param array $filters
     * @return PromiseInterface
     */
    public function fetch(int $page = 1, int $limit = 50, array $includes = [], array $includeFields = [], array $excludeFields = [], array $filters = []): PromiseInterface
    {
        $options = array(
            'query' => array(
                'limit' => $limit,
                'page' => $page
            )
        );
        
        $options['query'] = array_merge_recursive($options['query'], $filters);
        
        if (! empty($includes)) {
            $options['query'] = array_merge_recursive($options['query'], array(
                'include' => implode(', ', $includes)
            ));
        }
        
        if (! empty($includeFields)) {
            $options['query'] = array_merge_recursive($options['query'], array(
                'include_fields' => implode(', ', $includeFields)
            ));
        }
        
        if (! empty($excludeFields)) {
            $options['query'] = array_merge_recursive($options['query'], array(
                'exclude_fields' => implode(', ', $excludeFields)
            ));
        }
        
        return $this->httpClient->getAsync('catalog/products', $options);
    }

    /**
     * Fetches a single product by Id
     *
     * @param int $id
     * @param string[] $includes
     * @param array $includeFields
     * @param array $excludeFields
     * @param array $filters
     * @return PromiseInterface
     */
    public function fetchById(int $id, array $includes = [], array $includeFields = [], array $excludeFields = [], array $filters = []): PromiseInterface
    {
        $options = array(
            'query' => $filters
        );
        
        if (! empty($includes)) {
            $options['query'] = array_merge_recursive($options['query'], array(
                'include' => implode(', ', $includes)
            ));
        }
        
        if (! empty($includeFields)) {
            $options['query'] = array_merge_recursive($options['query'], array(
                'include_fields' => implode(', ', $includeFields)
            ));
        }
        
        if (! empty($excludeFields)) {
            $options['query'] = array_merge_recursive($options['query'], array(
                'exclude_fields' => implode(', ', $excludeFields)
            ));
        }
        
        return $this->httpClient->getAsync(sprintf('catalog/products/%d', $id), $options);
    }

    /**
     * Deletes products
     *
     * @return PromiseInterface
     */
    public function delete(array $options = []): PromiseInterface
    {
        return $this->httpClient->deleteAsync('catalog/products', array(
            'query' => $options
        ));
    }

    /**
     * Deletes a single product by Id
     *
     * @param int $id
     * @return PromiseInterface
     */
    public function deleteById(int $id): PromiseInterface
    {
        return $this->httpClient->deleteAsync(sprintf('catalog/products/%d', $id));
    }

    /**
     * Generates request to fetch all images for a given product Id
     *
     * @param int $productId
     * @param string[] $includeFields
     * @param string[] $excludeFields
     * @return PromiseInterface
     */
    public function fetchProductImages($productId, array $includeFields = [], array $excludeFields = []): PromiseInterface
    {
        $queries = [];
        
        if (! empty($includeFields)) {
            $queries['include_fields'] = implode(', ', $includeFields);
        }
        
        if (! empty($excludeFields)) {
            $queries['exclude_fields'] = implode(', ', $excludeFields);
        }
        
        return $this->httpClient->getAsync(sprintf('catalog/products/%d/images', $productId), array(
            'query' => $queries
        ));
    }

    /**
     * Generates request to fetch an image by Id for a given product
     *
     * @param int $productId
     * @param int $imageId
     * @param string[] $includeFields
     * @param string[] $excludeFields
     * @return PromiseInterface
     */
    public function fetchProductImageById(int $productId, int $imageId, array $includeFields = [], array $excludeFields = []): PromiseInterface
    {
        $queries = [];
        
        if (! empty($includeFields)) {
            $queries['include_fields'] = implode(', ', $includeFields);
        }
        
        if (! empty($excludeFields)) {
            $queries['exclude_fields'] = implode(', ', $excludeFields);
        }
        
        return $this->httpClient->getAsync(sprintf('catalog/products/%d/images/%d', $productId, $imageId), array(
            'query' => $queries
        ));
    }

    /**
     * Generates request to upload an image for a given product
     *
     * @param int $productId
     * @param string $filePath
     *            File path or URL
     * @return PromiseInterface
     */
    public function uploadProductImage(int $productId, string $filePath): PromiseInterface
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
        
        return $this->httpClient->postAsync(sprintf('catalog/products/%d/images', $productId), $options);
    }

    /**
     * Generates request to upload multiple images for a given product
     *
     * @param int $productId
     * @param string[] $filePaths
     * @return PromiseInterface
     */
    public function uploadProductImages($productId, array $filePaths): PromiseInterface
    {
        $promises = array_map(function ($filePath) use ($productId) {
            return $this->uploadProductImage($productId, $filePath);
        }, $filePaths);
        
        return $this->httpClient->batchExecute($promises);
    }

    /**
     * Generates request to update an image for a given product
     *
     * @param int $productId
     * @param int $imageId
     * @param string $filePath
     *            A file path or URL to an image
     * @return PromiseInterface
     */
    public function updateProductImage(int $productId, int $imageId, string $filePath): PromiseInterface
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
        
        return $this->httpClient->putAsync(sprintf('catalog/products/%d/images/%d', $productId, $imageId), $options);
    }

    /**
     * Generates request to delete an image for a given product
     *
     * @param int $productId
     * @param int $imageId
     * @return PromiseInterface
     */
    public function deleteProductImageById(int $productId, int $imageId): PromiseInterface
    {
        return $this->httpClient->deleteAsync(sprintf('catalog/products/%d/images/%d', $productId, $imageId));
    }

    /**
     * Generates request to create options for a given product
     *
     * @param int $productId
     * @param string $displayName
     * @param string $type
     * @param array $optionValues
     * @return PromiseInterface
     */
    public function createProductOption(int $productId, string $displayName, string $type, array $optionValues): PromiseInterface
    {
        $productOptions = array(
            'display_name' => $displayName,
            'type' => $type,
            'option_values' => $optionValues
        );
        
        return $this->httpClient->postAsync(sprintf('catalog/products/%d/options', $productId), array(
            'json' => $productOptions
        ));
    }

    /**
     *
     * @param int $productId
     * @param int $page
     * @param int $limit
     * @return PromiseInterface
     */
    public function fetchProductOptions(int $productId, int $page = 1, int $limit = 50): PromiseInterface
    {
        return $this->httpClient->postAsync(sprintf('catalog/products/%d/options', $productId), array(
            'query' => array(
                'page' => $page,
                'limit' => $limit
            )
        ));
    }

    /**
     * Generates request to to create a single value for option for a given
     * product
     *
     * @param int $productId
     * @param int $optionId
     * @param array $optionValue
     * @return PromiseInterface
     */
    public function createProductOptionValue(int $productId, int $optionId, array $optionValue): PromiseInterface
    {
        return $this->httpClient->postAsync(sprintf('catalog/products/%d/options/%d/values', $productId, $optionId), array(
            'json' => $optionValue
        ));
    }

    /**
     * Generates request to fetch values for a given product option
     *
     * @param int $productId
     * @param int $optionId
     * @param int $page
     * @param int $limit
     * @return PromiseInterface
     */
    public function fetchProductOptionValues(int $productId, int $optionId, int $page = 1, int $limit = 50): PromiseInterface
    {
        return $this->httpClient->postAsync(sprintf('catalog/products/%d/options/%d/values', $productId, $optionId), array(
            'query' => array(
                'page' => $page,
                'limit' => $limit
            )
        ));
    }

    /**
     * Generates request to fetech variants for a given product
     *
     * @param int $productId
     * @param int $page
     * @param int $limit
     * @param array $includeFields
     * @param array $excludeFields
     * @return PromiseInterface
     */
    public function fetchProductVariants(int $productId, int $page = 1, int $limit = 50, array $includeFields = [], array $excludeFields = []): PromiseInterface
    {
        $queries = [];
        
        if (! empty($includeFields)) {
            $queries['include_fields'] = implode(', ', $includeFields);
        }
        
        if (! empty($excludeFields)) {
            $queries['exclude_fields'] = implode(', ', $excludeFields);
        }
        
        return $this->httpClient->getAsync(sprintf('catalog/products/%d/variants', $productId), array(
            'query' => $queries
        ));
    }

    /**
     * Generates request to create a single variant for a given product
     *
     * @param int $productId
     * @param array $variant
     * @return PromiseInterface
     */
    protected function createProductVariant(int $productId, array $variant): PromiseInterface
    {
        return $this->httpClient->postAsync(sprintf('catalog/products/%d/variants', $productId), array(
            'json' => $variant
        ));
    }

    /**
     * Generates request to create a number variants for a given product
     *
     * @param int $productId
     * @param array ...$variants
     * @return PromiseInterface
     */
    public function createProductVariants(int $productId, array ...$variants): PromiseInterface
    {
        $promises = array_map(function ($variant) use ($productId) {
            return $this->createProductVariant($productId, $variant);
        }, $variants);
        
        return $this->httpClient->batchExecute($promises);
    }

    /**
     * Generates request to update a single variant for a given product
     *
     * @param int $productId
     * @param int $variantId
     * @param array $variant
     * @return PromiseInterface
     */
    public function updateProductVariant(int $productId, int $variantId, array $variant): PromiseInterface
    {
        return $this->httpClient->postAsync(sprintf('catalog/products/%d/variants/%d', $productId, $variantId), array(
            'json' => $variant
        ));
    }

    /**
     * Generates request to fetch brands
     *
     * @param int $page
     * @param int $limit
     * @return PromiseInterface
     */
    public function fetchBrands(int $page = 1, int $limit = 50): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->getAsync('brands');
    }

    /**
     * Generates request to fetch a single brand by id
     *
     * @param int $id
     * @return PromiseInterface
     */
    public function fetchBrandById(int $id): PromiseInterface
    {
        $url = sprintf('brands/%d', $id);
        
        return $this->httpClient->useLegacyConnection()->getAsync($url);
    }

    /**
     * Generates request to create a new brand
     *
     * @param array $brand
     * @return PromiseInterface
     */
    public function createBrand(array $brand): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->postAsync('brands', array(
            'json' => $brand
        ));
    }

    /**
     * Generates request to update an existing brand
     *
     * @param int $Id
     * @param array $brand
     * @return PromiseInterface
     */
    public function updateBrand(int $id, array $brand): PromiseInterface
    {
        $url = sprintf('brands/%d', $id);
        
        return $this->httpClient->useLegacyConnection()->putAsync($url, array(
            'json' => $brand
        ));
    }

    /**
     * Generates request to delete a single brand by id
     *
     * @param int $id
     * @return PromiseInterface
     */
    public function deleteBrandById(int $id): PromiseInterface
    {
        $url = sprintf('brands/%d', $id);
        
        return $this->httpClient->useLegacyConnection()->deleteAsync($url);
    }
}