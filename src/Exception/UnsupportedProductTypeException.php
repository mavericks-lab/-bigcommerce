<?php
namespace Maverickslab\BigCommerce\Exception;

use Maverickslab\BigCommerce\Model\Product;

class UnsupportedProductTypeException extends BigCommerceException
{

    public function __construct($type)
    {
        $message = sprintf('"%s" is not valid product type. Supported types include %s.', $type, implode(', ', Product::$types));
        parent::__construct($message);
    }
}