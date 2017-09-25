<?php
declare(strict_types = 1);
namespace Maverickslab\BigCommerce;

use Maverickslab\BigCommerce\Http\Request\CategoryRequest;
use Maverickslab\BigCommerce\Http\Request\ProductRequest;
use Maverickslab\BigCommerce\Http\Request\OrderRequest;
use Maverickslab\BigCommerce\Http\Request\CustomerRequest;
use Maverickslab\BigCommerce\Http\Request\MerchantRequest;
use Maverickslab\BigCommerce\Http\Client\BigCommerceHttpClient;

/**
 * BigCommerce API class
 *
 * @author cosman
 *        
 */
class BigCommerce
{

    const API_VERSION = 'v3';

    /**
     *
     * @var CategoryRequest
     */
    protected $categoryRequest;

    /**
     *
     * @var ProductRequest
     */
    protected $productRequest;

    /**
     *
     * @var OrderRequest
     */
    protected $orderRequest;

    /**
     *
     * @var CustomerRequest
     */
    protected $customerRequest;

    /**
     *
     * @var MerchantRequest
     */
    protected $merchantRequest;

    /**
     *
     * @param BigCommerceHttpClient $httpClient
     */
    public function __construct(BigCommerceHttpClient $httpClient)
    {
        $this->categoryRequest = new CategoryRequest($httpClient);
        $this->productRequest = new ProductRequest($httpClient);
        $this->orderRequest = new OrderRequest($httpClient);
        $this->customerRequest = new CustomerRequest($httpClient);
        $this->merchantRequest = new MerchantRequest($httpClient);
    }

    /**
     * Returns category managing resquest
     *
     * @return CategoryRequest
     */
    public function category(): CategoryRequest
    {
        return $this->categoryRequest;
    }

    /**
     * Returns product managing request
     *
     * @return ProductRequest
     */
    public function product(): ProductRequest
    {
        return $this->productRequest;
    }

    /**
     * Returns order managing request
     *
     * @return OrderRequest
     */
    public function order(): OrderRequest
    {
        return $this->orderRequest;
    }

    /**
     * Returns customer managing request
     *
     * @return CustomerRequest
     */
    public function customer(): CustomerRequest
    {
        return $this->customerRequest;
    }

    /**
     * Returns merchant managing request
     *
     * @return MerchantRequest
     */
    public function merchant(): MerchantRequest
    {
        return $this->merchantRequest;
    }
}