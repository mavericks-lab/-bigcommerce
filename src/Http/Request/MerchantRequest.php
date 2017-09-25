<?php
declare(strict_types = 1);
namespace Maverickslab\BigCommerce\Http\Request;

use GuzzleHttp\Promise\PromiseInterface;

/**
 * Request class for interacting with merchant/store end point on BigCommerce
 *
 * @author cosman
 *        
 */
class MerchantRequest extends BaseRequest
{

    /**
     * Generates request to fetch merchant/store details
     *
     * @return PromiseInterface
     */
    public function fetchDetails(): PromiseInterface
    {
        return $this->httpClient->useLegacyConnection()->getAsync('store');
    }
}
