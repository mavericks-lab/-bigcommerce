<?php
declare(strict_types = 1);
namespace Maverickslab\BigCommerce;

use Maverickslab\BigCommerce\Http\Request\CategoryRequest;
use Maverickslab\BigCommerce\Http\Request\ProductRequest;
use Maverickslab\BigCommerce\Http\Request\OrderRequest;
use Maverickslab\BigCommerce\Http\Request\CustomerRequest;
use Maverickslab\BigCommerce\Http\Request\MerchantRequest;
use Maverickslab\BigCommerce\Http\Client\BigCommerceHttpClient;
use Maverickslab\BigCommerce\Http\Request\OptionRequest;

/**
 * BigCommerce API class
 *
 * @author cosman
 *        
 */
class BigCommerce
{

    /**
     * Version
     *
     * @var string
     */
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
     * @var OptionRequest
     */
    protected $optionRequest;

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
        $this->optionRequest = new OptionRequest($httpClient);
    }

    /**
     * Returns category managing resquest
     *
     * @return \Maverickslab\BigCommerce\Http\Request\CategoryRequest
     */
    public function category(): CategoryRequest
    {
        return $this->categoryRequest;
    }

    /**
     * Returns product managing request
     *
     * @return \Maverickslab\BigCommerce\Http\Request\ProductRequest
     */
    public function product(): ProductRequest
    {
        return $this->productRequest;
    }

    /**
     * Returns option managing request
     *
     * @return \Maverickslab\BigCommerce\Http\Request\OptionRequest
     */
    public function option(): OptionRequest
    {
        return $this->optionRequest;
    }

    /**
     * Returns order managing request
     *
     * @return \Maverickslab\BigCommerce\Http\Request\OrderRequest
     */
    public function order(): OrderRequest
    {
        return $this->orderRequest;
    }

    /**
     * Returns customer managing request
     *
     * @return \Maverickslab\BigCommerce\Http\Request\CustomerRequest
     */
    public function customer(): CustomerRequest
    {
        return $this->customerRequest;
    }

    /**
     * Returns merchant managing request
     *
     * @return \Maverickslab\BigCommerce\Http\Request\MerchantRequest
     */
    public function merchant(): MerchantRequest
    {
        return $this->merchantRequest;
    }
}