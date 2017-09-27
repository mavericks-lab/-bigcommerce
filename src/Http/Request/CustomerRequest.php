<?php
declare(strict_types = 1);
namespace Maverickslab\BigCommerce\Http\Request;

use GuzzleHttp\Promise\PromiseInterface;

/**
 * Class to handler customer/subscriber related requests
 *
 * @author cosman
 *        
 */
class CustomerRequest extends BaseRequest
{

    /**
     * Generates request to fetch a number of customers
     *
     * @param int $page
     * @param int $limit
     * @param array $filters
     *            Additional filters
     * @return PromiseInterface 
     */
    public function fetch(int $page = 1, int $limit = 50, array $filters = []): PromiseInterface
    {
        $queries = $filters;
        
        $filters = array_merge($queries, array(
            'page' => $page,
            'limit' => $limit
        ));
        
        return $this->httpClient->useLegacyConnection()->getAsync('customers', array(
            'query' => $queries
        ));
    }

    /**
     * Generates request to fetch a single customer by Id
     *
     * @param int $customerId
     * @param array $filters
     * @return PromiseInterface
     */
    public function fetchById(int $customerId, array $filters = []): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->getAsync(sprintf('customers/%d', $customerId), array(
            'query' => $filters
        ));
    }

    /**
     * Generates request to create a new customer
     *
     * @param array $customerInfo
     * @return PromiseInterface
     */
    public function create(array $customerInfo): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->postAsync('customers', array(
            'json' => $customerInfo
        ));
    }

    /**
     * Generates request to delete customer
     *
     * @param array $filters
     * @return PromiseInterface
     */
    public function delete(array $filters = []): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->deleteAsync('customers', array(
            'query' => $filters
        ));
    }

    /**
     * Generates request to delete a given customer by Id
     *
     * @param int $customerId
     * @return PromiseInterface
     */
    public function deleteById(int $customerId): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->deleteAsync(sprintf('customers/%d', $customerId));
    }

    /**
     * Generates request to update a given customer
     *
     * @param int $customerId
     * @param array $customerInfo
     * @return PromiseInterface
     */
    public function update(int $customerId, array $customerInfo): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->putAsync(sprintf('customers/%d', $customerId), array(
            'json' => $customerInfo
        ));
    }
}