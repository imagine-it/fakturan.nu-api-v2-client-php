#Fakturan.nu REST API client PHP
API-client in PHP for the web based software of [Fakturan.nu](https://fakturan.nu). Documentation can be viewed at [API documentation](https://sandbox.fakturan.nu/apidocs/).

##Getting started

###Requirements
- PHP 5.4+

###Installation
You can install the library via [Composer](http://getcomposer.org) by adding the following line to the require block of your composer.json file:

```json
"fakturan.nu/fakturan": "1.0.0"
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
try 
{
	Fakturan\Model\Product::all();
}
catch(Fakturan\Error\FakturanException $e)
{
	// You can catch all errors with Fakturan\Error\FakturanException. For more specific exceptions see the [Errors section](#user-content-errors).
}
```
Create a new product

```php
$new_product = new Fakturan\Model\Product();
$new_product->name = 'My new shiny product';
$new_product->unit = 'KG';
$new_product->price = 450;
try
{
	$new_product->save();
}
catch(Fakturan\Error\FakturanException $e)
{
	// You can catch all errors with Fakturan\Error\FakturanException. For more specific exceptions see the [Errors section](#user-content-errors).
}
```
	
Get a single product with id 54

```php
try {
	$book = Fakturan\Model\Product::find(54);
}
catch(Fakturan\Error\FakturanException $e)
{
	// You can catch all errors with Fakturan\Error\FakturanException. For more specific exceptions see the [Errors section](#user-content-errors).
}
```
	
Edit product

```php
try {
	$book = Fakturan\Model\Product::find(54);
	$book->price = 25;
	$book->save();
}
catch(Fakturan\Error\FakturanException $e)
{
	// You can catch all errors with Fakturan\Error\FakturanException. For more specific exceptions see the [Errors section](#user-content-errors).
}
```
	
Delete a product

```php
$product_to_be_deleted = Fakturan\Model\Product::find(54);
try {
	$product_to_be_deleted->destroy();
}
catch(Fakturan\Error\FakturanException $e)
{
	// You can catch all errors with Fakturan\Error\FakturanException. For more specific exceptions see the [Errors section](#user-content-errors).
}
```	


###Errors
The API will answer with different kinds of Exceptions depending on type of issue:

```php
Fakturan\Error\AccessDenied 			// Catches 401 (access denied) 
Fakturan\Error\ResourceNotFound 	// Catches 404 (Resource not found). 
Fakturan\Error\ConnectionFailed 	// Catches 407 (Connection to server failed).
Fakturan\Error\ResourceInvalid 		// Catches 422 (Validation errors).
Fakturan\Error\ClientError				// catches 400-499 (Client errors).
Fakturan\Error\ServerError				// Catches 500-599 (Server related issues).
Fakturan\Error\FakturanException 	// Catches all of the above
```

As they all inherits from `Fakturan\Error\FakturanException` they can all be caught within one catch-block:

```php
try {

}
catch(Fakturan\Error\FakturanException $e) 
{
	echo $e->getMessage();
}
```