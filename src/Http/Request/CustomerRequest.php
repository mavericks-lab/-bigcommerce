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
        $filters['page'] = $page;
        $filters['limit'] = $limit;
        
        return $this->httpClient->useLegacyConnection()->getAsync('customers', array(
            'query' => $filters
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

    /**
     * Generates request to fetch addresses belonging to a given customer
     *
     * @param int $customerId
     * @return PromiseInterface
     */
    public function fetchAddresses(int $customerId): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->getAsync(sprintf('customers/%d/addresses', $customerId));
    }

    /**
     * Creates a single a ddress for a customer
     *
     * @param int $customerId
     * @param array $address
     * @return PromiseInterface
     */
    public function createAddress(int $customerId, array $address): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->postAsync(sprintf('customers/%d/addresses', $customerId), array(
            'json' => $address
        ));
    }

    /**
     * Creates at least one address for a customer
     *
     * @param int $customerId
     * @param array ...$addresses
     * @return PromiseInterface
     */
    public function createAddresses(int $customerId, array ...$addresses): PromiseInterface
    {
        $promises = array_map(function ($address) use ($customerId) {
            return $this->createAddress($customerId, $address);
        }, $addresses);
        
        return $this->resolvePromises($promises);
    }

    /**
     * Generates request to update a single a ddress for a customer
     *
     * @param int $customerId
     * @param int $addressId
     * @param array $address
     * @return PromiseInterface
     */
    public function updateAddress(int $customerId, int $addressId, array $address): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->putAsync(sprintf('customers/%d/addresses/%d', $customerId, $addressId), array(
            'json' => $address
        ));
    }

    /**
     * Generates request to delete a single a ddress for a customer
     *
     * @param int $customerId
     * @param int $addressId
     * @return PromiseInterface
     */
    public function deleteAddress(int $customerId, int $addressId): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->deleteAsync(sprintf('customers/%d/addresses/%d', $customerId, $addressId));
    }

    /**
     * Generates request to delete multiple addreses for a customer
     *
     * @param int $customerId
     * @param array $filters
     * @return PromiseInterface
     */
    public function deleteAddresses(int $customerId, array $filters = []): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->deleteAsync(sprintf('customers/%d/addresses', $customerId), array(
            'query' => $filters
        ));
    }
}