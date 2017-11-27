<?php
declare(strict_types = 1);
namespace Maverickslab\BigCommerce\Http\Request;

use GuzzleHttp\Promise\PromiseInterface;

/**
 * Option request class
 *
 * @author cosman
 *        
 */
class OptionRequest extends BaseRequest
{

    /**
     * Generates request to fetch number of options
     *
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function count(): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->getAsync('options/count');
    }

    /**
     * Generates request to fetches a number of options
     *
     * @param int $page
     * @param int $limit
     * @param array $filters
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function fetch(int $page = 1, int $limit = 250, array $filters = []): PromiseInterface
    {
        $filters['page'] = $page;
        $filters['limit'] = $limit;
        
        $options = array(
            'query' => $filters
        );
        
        return $this->httpClient->getAsync('catalog/options', $options);
    }

    /**
     * Generates request to fetch a single option by Id
     *
     * @param int $id
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function fetchById(int $id): PromiseInterface
    {
        return $this->httpClient->getAsync(sprintf('catalog/options/%d', $id));
    }

    /**
     * Creates an option
     *
     * @param array $option
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function create(array $option): PromiseInterface
    {
        return $this->httpClient->postAsync('catalog/options', array(
            'json' => $option
        ));
    }

    /**
     * Generates request to update a single option
     *
     * @param int $id
     * @param array $option
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function update(int $id, array $option): PromiseInterface
    {
        return $this->httpClient->putAsync(sprintf('catalog/options/%d', $id), array(
            'json' => $option
        ));
    }

    /**
     * Generates request to delete a number of options
     *
     * @param int ...$ids
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function delete(int ...$ids): PromiseInterface
    {
        $promises = array_map(function (int $id) {
            return $this->httpClient->deleteAsync(sprintf('catalog/options/%d', $id));
        }, $ids);
        
        return $this->resolvePromises($promises);
    }
}