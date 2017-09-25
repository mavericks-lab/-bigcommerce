<?php
declare(strict_types = 1);
namespace Maverickslab\BigCommerce\Http\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;

class BigCommerceHttpClient extends Client
{

    const VERSION = 3;

    const LEGACY_VERSION = 2;

    /**
     *
     * @var string
     */
    protected $accessToken;

    /**
     *
     * @var string
     */
    protected $clientId;

    /**
     *
     * @var string
     */
    protected $clientSecret;

    /**
     *
     * @var string
     */
    protected $storeId;

    /**
     *
     * @var string
     */
    protected $baseUrl;

    /**
     *
     * @var string
     */
    protected $legacyBaseUrl;

    /**
     *
     * @var array
     */
    protected $headers = [];

    /**
     *
     * @var Client
     */
    protected $legacyConnection;

    /**
     *
     * @param string $accessToken
     * @param string $clientId
     * @param string $clientSecret
     * @param string $storeId
     * @param array $config
     */
    public function __construct(string $accessToken, string $clientId, string $clientSecret, string $storeId, array $config = [])
    {
        $this->baseUrl = sprintf('https://api.bigcommerce.com/stores/%s/v%d/', $storeId, static::VERSION);
        
        $this->legacyBaseUrl = sprintf('https://api.bigcommerce.com/stores/%s/v%d/', $storeId, static::LEGACY_VERSION);
        
        $this->headers = array(
            'X-Auth-Token' => $accessToken,
            'X-Auth-Client' => $clientId,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        );
        
        if (! array_key_exists('headers', $config)) {
            $config['headers'] = array();
        }
        
        $config['headers'] = array_merge_recursive($config['headers'], $this->headers);
        
        $config['base_uri'] = $this->baseUrl;
        
        parent::__construct($config);
        
        $this->accessToken = $accessToken;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->storeId = $storeId;
        
        $config['base_uri'] = $this->legacyBaseUrl;
        
        $this->legacyConnection = new Client($config);
    }

    /**
     * Resolves an array of promises
     *
     * @param array $promises
     * @param bool $abortOnError
     * @return PromiseInterface
     */
    public function batchExecute(array $promises, bool $abortOnError = true): PromiseInterface
    {
        return $abortOnError ? \GuzzleHttp\Promise\all($promises) : \GuzzleHttp\Promise\settle($promises);
    }

    /**
     * Returns an http client that has its base URI pointing to the legacy (v2) of the big commerce api
     *
     * @return Client
     */
    public function useLegacyConnection(): Client
    {
        return $this->legacyConnection;
    }

    /**
     * Creates and returns a new instance of this class
     * 
     * @param string $accessToken
     * @param string $clientId
     * @param string $clientSecret
     * @param string $storeId
     * @param array $config
     * @return self
     */
    public static function createInstance(string $accessToken, string $clientId, string $clientSecret, string $storeId, array $config = []): self
    {
        return new static($accessToken, $clientId, $clientSecret, $storeId, $config = []);
    }
}
