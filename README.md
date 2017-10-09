## BIGCOMMERCE REQUESTS PACKAGE

### Description
This package serves as a request mapper for BigCommerce products, orders, customer, and merchant end points.

### Installation
```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://bitbucket.org/maverickslab/bigcommerce"
        }
    ],
    "require": {
        "mavericks-lab/bigcommerce": "dev-master"
    }
}

```
### Initialization
```
use Maverickslab\BigCommerce\Http\Client\BigCommerceHttpClient;
use Maverickslab\BigCommerce\Http\Request\{
    ProductRequest, CategoryRequest, OrderRequest, MerchantRequest, CustomerRequest
};

$authToken = 'h5wm9usftmq7zkmn1bggaj6cr8h3ml3';
$clientId = 'nbioblf5th4u4y9iqhtbwbjyl6qb6jt';
$clientSecret = '';
$storeId = '9ma06';

$httpClient = new BigCommerceHttpClient($authToken, $clientId, $clientSecret, $storeId);

//Create an instance of a product request
$productRequest = new ProductRequest($httpClient);

//Create an instance of a category request
$categoryRequest = new CategoryRequest($httpClient);

//Create an instance of an order request
$orderRequest = new OrderRequest($httpClient);

//Create an instance of a merchant request
$merchantRequest = new MerchantRequest($httpClient);

//Create an instance of a customer request
$customerRequest = new CustomerRequest($httpClient);
```
You can also create an instance of `Maverickslab\BigCommerce\BigCommerce`, which combines all the above request into one object.
```
use Maverickslab\BigCommerce\BigCommerce;

$bigCommerce = new BigCommerce($httpClient);

//Get instance of a ProductRequest
$bigCommerce->product();

//Get instance of a CategoryRequest
$bigCommerce->category();

//Get instance of an OrderRequest
$bigCommerce->order();

//Get instance of a CustomerRequest
$bigCommerce->customer();

Get instance of a MerchantRequest
$bigCommerce->merchant();
```
**NOTE: All publicly accessible methods in these repositories return instances of `Psr\Http\Message\ResponseInterface`, so you must resolve them to get their actual return values.**

### Working with product requests

#### Creating a product
```

$product = array(
    'name' => 'Dell XPS 15',
    'weight' => 10.5,
    'price' => 1500,
    'sku' => 'DELL-XPS-15'
);

$promise = $productRequest->create($product);
```

#### Updating a product
```
$id = 1;
$productInfo = array(
    'name' => 'Dell XPS 13'
);

$promise = $productRequest->update($id, $productInfo);
```

#### Fetching a collection of products

```
$page = 1;
$limit = 1000;
$includes = ['images', 'variants'];

$promise = $productRequest->fetch($page, $limit, $includes);
```

#### Fetching a single product
```
$id = 1;
$promise = $productRequest->fetchById($id);
```

#### Deleting products
```
$id = 1;
$promise = $productRequest->deleteById($id);
```

#### Fetch images for a product

```
$productId = 1;

$promise = $productRequest->fetchProductImages($productId);
```

#### Upload an image for a product
```
$productId = 1;

//External image
$imageFile = 'http://www.somedomain.com/images/product1.jpeg';

//Or
//Local image
$imageFile = 'images/product1.png';

$promise = $productRequest->uploadProductImage($productId, $imageFile);
```
**See `Maverickslab\BigCommerce\Http\Request\ProductRequest` for more information on how to perform other product related requests.**

### Working with categories requests

#### Creating a category
```
$category = array(
    'name' => 'Cars',
    'parent_id' => 0
);

$promise = $categoryRequest->create($category);
```

#### Updating a category
```
$categoryId = 1;
$categoryInfo = array(
    'name' => 'Bikes',
    'parent_id' => 1
);

$promise = $categoryRequest->update($categoryId, $categoryInfo);
```

#### Fetching a collection categories
```
$page = 1;
$limit = 500;

$promise = $categoryRequest->fetch($page, $limit);

```
**See `Maverickslab\BigCommerce\Http\Request\CategoryRequest` for more on how to perform other category related requests.**

### Working with orders requests

#### Fetching a collection of orders
```
$page = 1;
$limit = 500;
$filters = [];

$promise = $orderRequest->fetch($page, $limit, $filters);
```

#### Updating orders

```
$orderId = 1;
$orderInfo = array(
    'status_id' => 10
);

$promise = $orderRequest->update($orderId, $orderInfo);
```

#### Fetching ordered products

```
$orderId = 1;
$page = 1;
$limit = 250;

$promise = $orderRequest->fetchOrderedProducts($orderId, $page, $limit);
```

**See `Maverickslab\BigCommerce\Http\Request\OrderRequest` for more on how to perform other order related requests.**

### Working with customers requests

#### Fetching a collection of customers

```
$page = 1;
$limit = 250;
$filters = [];
$promise = $customerRequest->fetch($page, $limit, $filters);
```

### Fetching a single customer

```
$customerId = 2;

$promise = $customerRequest->fetchById($customerId);
```

#### Creating a customer

```
$customer = array(
    'first_name' => 'John',
    'last_name' => 'Smith',
    'email' => 'johnsmith@somedomain.com',
    'phone' => '+2335457487484'
);

$promise = $customerRequest->create($customer);
```

**See `Maverickslab\BigCommerce\Http\Request\CustomerRequest` for more on how to perform other customer related requests.**

### Working with merchant requests

#### Fetching merchant information
```
$promise = $merchantRequest->fetchDetails();
```

### Resolving a promise
```
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

$promise->then(function(ResponseInterface $response){
    //Do something wih the response
}, function(RequestException $e){
    echo $e->getMessage();
});
```