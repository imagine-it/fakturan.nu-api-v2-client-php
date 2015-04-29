#Fakturan.nu REST API client PHP
API-client in PHP for the web based software of [Fakturan.nu](https://fakturan.nu). Documentation can be viewed at [API documentation](https://fakturan.nu/apidocs/).

##Getting started

###Requirements
- PHP 5.4+

###Installation
You can install the library via [Composer](http://getcomposer.org) by adding the following line to the require block of your composer.json file:

```json
"fakturan.nu/fakturan": "1.0.*"
```
  
followed by running `composer install`.

###Setup
You authenticate by simply adding your encrypted `key_id` and `password` and then you're good to go! Remember to use sandbox-mode in development. When ready to use in production you can just remove the third parameter.

```php
Fakturan\Fakturan::setup('KEY_ID', 'PASSWORD', [
  'sandbox' => TRUE 
]);
```

###Examples
Retrieve all your products

```php
Fakturan\Model\Product::all();
```
Create a new product

```php
$new_product = new Fakturan\Product();
$new_product->name = 'My new shiny product';
$new_product->unit = 'KG';
$new_product->price = 450;

$new_product->save();
```
  
Get a single product with id 54

```php
$book = Fakturan\Product::find(54);
```
  
Edit product

```php
$book = Fakturan\Product::find(54);
$book->price = 25;
$book->save();
```
  
Delete a product

```php
$product_to_be_deleted = Fakturan\Product::find(54);
$product_to_be_deleted->destroy();
``` 

Create an invoice
```php
// Find your client
$client = Fakturan\Client::find(1);

$invoice = new Fakturan\Invoice();
$invoice->client_id = $client->id;
$invoice->date = date('Y-m-d');

// Find a product to use for templating
$product = Fakturan\Product::find(24);

// Add the product to the invoice. The second parameter can override the default values.
// It is used to set the amount and makes it possible to add a discount.
// See https://fakturan.nu/apidocs/2/invoices/create.html for possible attributes on rows.
$invoice->addRow($product, ['amount' => 5]);

// It is also possible to add rows without a preset product by sending an array instead:
$row = [
	'product_name' => 'My custom product',
	'unit' => 'PCS',
	'price' => 500,
	'amount' => 24
];
$invoice->addRow($row)

// Rows only consisting of text can also be added
$invoice->addRow(['text_row' => true, 'text' => 'Performed customizations: purple flames']);

$invoice->save();

```


###Errors
The API will answer with different kinds of Exceptions depending on type of issue:

```php
Fakturan\Error\AccessDenied       // Catches 401 (access denied) 
Fakturan\Error\ResourceNotFound   // Catches 404 (Resource not found). 
Fakturan\Error\ConnectionFailed   // Catches 407 (Connection to server failed).
Fakturan\Error\ResourceInvalid    // Catches 422 (Validation errors).
Fakturan\Error\ClientError        // Catches 400-499 (Client errors).
Fakturan\Error\ServerError        // Catches 500-599 (Server related issues).
Fakturan\Error\FakturanException  // Catches all of the above
```

As they all inherits from `Fakturan\Error\FakturanException` they can all be caught within one catch-block:

```php
try {
	// Methods that sends a request to the server
}
catch(Fakturan\Error\FakturanException $e) 
{
  echo $e->getMessage();
}
```

When in production we recommend you to always use a try-catch block when using `save`, `update_attributes` and `delete`