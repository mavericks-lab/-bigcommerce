<?php
declare(strict_types = 1);
namespace Maverickslab\BigCommerce\Http\Request;

use GuzzleHttp\Promise\PromiseInterface;

/**
 * Request class for managing merchant order
 *
 * @author cosman
 *        
 */
class OrderRequest extends BaseRequest
{

    /**
     * Generates request to fetch number of orders
     *
     * @return PromiseInterface
     */
    public function count(): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->getAsync('orders/count');
    }

    /**
     * Generates request to fetch a number of orders for a store
     *
     * @param int $page
     * @param int $limit
     * @return PromiseInterface
     */
    public function fetch(int $page = 1, int $limit = 50): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->getAsync('orders');
    }

    /**
     * Generates request to fetch a single order by Id
     *
     * @param int $orderId
     * @return PromiseInterface
     */
    public function fetchById(int $orderId): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->getAsync(sprintf('orders/%d', $orderId));
    }

    /**
     * Generates request to update a given order
     *
     * @param int $orderId
     * @param array $orderInfo
     * @return PromiseInterface
     */
    public function update(int $orderId, array $orderInfo): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->putAsync(sprintf('orders/%d', $orderId), array(
            'json' => $orderInfo
        ));
    }

    /**
     * Generates request to fetch a number of products in a given order
     *
     * @param int $orderId
     * @param int $page
     * @param int $limit
     * @return PromiseInterface
     */
    public function fetchOrderedProducts(int $orderId, int $page = 1, int $limit = 50): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->getAsync(sprintf('orders/%d/products', $orderId), array(
            'query' => array(
                'page' => $page,
                'limit' => $limit
            )
        ));
    }

    /**
     * Genereates request to fetch a single product from a given order
     *
     * @param int $orderId
     * @param int $productId
     * @return PromiseInterface
     */
    public function fetchOrderedProductById(int $orderId, int $productId): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->getAsync(sprintf('orders/%d/products/%d', $orderId, $productId));
    }

    /**
     * Generates request to fetch order statuses
     *
     * @return PromiseInterface
     */
    public function fetchOrderStatuses(): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->getAsync('order_statuses');
    }

    /**
     * Generates request to fetch order status by Id
     *
     * @param int $id
     * @return PromiseInterface
     */
    public function fetchOrderStatusById(int $id): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->getAsync(sprintf('order_statuses/%d', $id));
    }

    /**
     * Generates request to fetch transactions for a given order
     *
     * @param int $orderId
     * @return PromiseInterface
     */
    public function fetchOrderTransactions(int $orderId): PromiseInterface
    {
        return $this->httpClient->getAsync(sprintf('orders/%d/transactions', $orderId));
    }
}