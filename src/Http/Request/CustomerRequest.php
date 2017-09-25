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
     * Generates request to fetch a number of customers/subscribers
     *
     * @param int $page
     * @param int $limit
     * @param array $filters
     *            Additional filters
     * @return PromiseInterface
     */
    public function fetchSubscribers(int $page = 1, int $limit = 50, array $filters = []): PromiseInterface
    {
        $queries = $filters;
        
        $filters = array_merge($queries, array(
            'page' => $page,
            'limit' => $limit
        ));
        
        return $this->httpClient->getAsync('customers/subscribers', array(
            'query' => $queries
        ));
    }

    /**
     * Generates request to fetch a single subscriber/customer by Id
     *
     * @param int $subscriberId
     * @param array $filters
     * @return PromiseInterface
     */
    public function fetchSubscriberById(int $subscriberId, array $filters = []): PromiseInterface
    {
        return $this->httpClient->getAsync(sprintf('customers/subscribers/%d', $subscriberId), array(
            'query' => $filters
        ));
    }

    /**
     * Generates request to create a new customer/subscriber
     *
     * @param array $customerInfo
     * @return PromiseInterface
     */
    public function createSubscriber(array $customerInfo): PromiseInterface
    {
        return $this->httpClient->postAsync('customers/subscribers', array(
            'json' => $customerInfo
        ));
    }

    /**
     * Generates request to delete customer
     *
     * @param array $filters
     * @return PromiseInterface
     */
    public function deleteSubscribers(array $filters = []): PromiseInterface
    {
        return $this->httpClient->deleteAsync('customers/subscribers', array(
            'query' => $filters
        ));
    }

    /**
     * Generates request to delete a given customer/subscriber by Id
     *
     * @param int $subscriberId
     * @return PromiseInterface
     */
    public function deleteSubscriberById(int $subscriberId): PromiseInterface
    {
        return $this->httpClient->deleteAsync(sprintf('customers/subscribers/%d', $subscriberId));
    }

    /**
     * Generates request to update a given subscriber/customer
     *
     * @param int $subscriberId
     * @param array $subscriberInfo
     * @return PromiseInterface
     */
    public function updateSubscriber(int $subscriberId, array $subscriberInfo): PromiseInterface
    {
        return $this->httpClient->putAsync(sprintf('customers/subscribers/%d', $subscriberId), array(
            'json' => $subscriberInfo
        ));
    }
}