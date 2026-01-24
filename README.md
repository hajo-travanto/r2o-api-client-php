ready2order PHP API
=============

PHP client library for the ready2order POS API v1. Wraps the REST API at [api.ready2order.com](https://api.ready2order.com).

## Getting Started

1. Register as a developer at [api.ready2order.com](https://api.ready2order.com) to obtain your "Developer Token"
2. Use the developer token to request access to existing accounts and obtain an "Account Token"
3. Use the "Account Token" for your API requests

For full API documentation, see [ready2order.com/api/doc](https://ready2order.com/api/doc).

## Installation

Install via Composer:

```bash
composer require ready2order/r2o-api-client-php
```

## Basic Usage

```php
use ready2order\Client;

$client = new Client('your-account-token');

// Fetch account information
$company = $client->get('company');
print_r($company);
```

## HTTP Methods

The client supports all standard HTTP methods:

```php
// GET - Retrieve resources
$products = $client->get('products');
$product = $client->get('products/123');

// POST - Create resources
$newProduct = $client->post('products', [
    'product_name' => 'Coffee',
    'product_price' => '3.50',
]);

// PUT - Create or replace resources
$productGroup = $client->put('productgroups', [
    'productgroup_name' => 'Beverages',
]);

// PATCH - Update resources
$updated = $client->patch('products/123', [
    'product_price' => '4.00',
]);

// DELETE - Remove resources
$client->delete('products/123');
```

## Configuration Options

### Custom Timeout

Set a custom timeout for all requests (default: 10 seconds):

```php
$client = new Client('your-token');
$client->setTimeout(30); // 30 seconds

// Or override per request (last parameter)
$result = $client->get('products', [], 60); // 60 second timeout
```

### Language Header

Set the Accept-Language header for localized responses:

```php
$client = new Client('your-token');
$client->setLanguage('de-DE'); // German
$client->setLanguage('en-US'); // English (default)
```

### Custom API Endpoint

For testing or custom deployments, pass the endpoint as the second constructor parameter:

```php
$client = new Client('your-token', 'https://custom-api.example.com/v1');
```

## Error Handling

The client throws specific exceptions for different error scenarios:

```php
use ready2order\Client;
use ready2order\Exceptions\ErrorResponseException;
use ready2order\Exceptions\ResourceNotFoundException;
use ready2order\Exceptions\InvalidResponseException;

$client = new Client('your-token');

try {
    $product = $client->get('products/999999');
} catch (ResourceNotFoundException $e) {
    // HTTP 404 - Resource not found
    echo "Product not found: " . $e->getMessage();
} catch (ErrorResponseException $e) {
    // API returned an error (4xx/5xx)
    echo "API error: " . $e->getMessage();

    // Access full error response data
    $errorData = $e->getData();
    if ($errorData) {
        print_r($errorData);
    }
} catch (InvalidResponseException $e) {
    // Response could not be JSON-decoded
    echo "Invalid response from API";
}
```

## Pagination

List endpoints support pagination via query parameters:

```php
$client = new Client('your-token');

// Fetch first page of products (default limit varies by endpoint)
$page1 = $client->get('products', [
    'page' => 1,
    'limit' => 50,
]);

// Fetch next page
$page2 = $client->get('products', [
    'page' => 2,
    'limit' => 50,
]);
```

## Complete Example

```php
use ready2order\Client;
use ready2order\Exceptions\ErrorResponseException;

$client = new Client('your-token');

// Create a product group
$productGroup = $client->put('productgroups', [
    'productgroup_name' => 'Soft drinks',
]);

// Create a product in that group
$product = $client->put('products', [
    'product_name' => 'Cola',
    'product_price' => '2.50',
    'product_vat' => '20',
    'productgroup' => [
        'productgroup_id' => $productGroup['productgroup_id'],
    ],
]);

echo "Created product: " . $product['product_name'];
```

## Requirements

- PHP 7.4 or higher
- ext-curl
- ext-json
- guzzlehttp/guzzle ^7.0

## License

MIT
