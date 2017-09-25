<?php
declare(strict_types = 1);
namespace Maverickslab\BigCommerce\Http\Request;

use Maverickslab\BigCommerce\Http\Client\BigCommerceHttpClient;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * Base class for all request classes
 *
 * @author cosman
 *        
 */
abstract class BaseRequest
{

    /**
     *
     * @var BigCommerceHttpClient
     */
    protected $httpClient;

    /**
     *
     * @param BigCommerceHttpClient $httpClient
     */
    public function __construct(BigCommerceHttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Resolves a collection of promises
     *
     * @param PromiseInterface[] $promises
     * @param bool $throwOnError
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function resolvePromises(array $promises, bool $throwOnError = true): PromiseInterface
    {
        return $this->httpClient->batchExecute($promises, $throwOnError);
    }

    /**
     * Signs a given data with client's secret
     *
     * @param mixed $data
     */
    protected function signData($data)
    {
        
        // TODO: Signing to be implemented later
        return $data;
    }
}