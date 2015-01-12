#Fakturan.nu REST API client PHP
API-client in PHP for the web based software of [Fakturan.nu](https://fakturan.nu) based on [Trucker](https://github.com/Indatus/trucker). Documentation can be viewed at then [API documentation](https://fakturan.nu/apidocs/v2/).

##Getting started

###Requirements
- PHP 5.4+

###Installation
You can install the library via [Composer](http://getcomposer.org) by adding the following line to the require block of your composer.json file:

	"fakturan.nu/fakturan": "1.0.0"
	
followed by running `composer install`.

###Authentication
You authenticate by simply adding your encrypted `key_id` and `password` and then you're good to go!

	Fakturan\Fakturan::setup('KEY_ID', 'PASSWORD');

###Examples
Retrieve all your products

	Fakturan\Model\Product::all();

Create a new product

	$new_product = new Fakturan\Model\Product();
	$new_product->name = 'My new shiny product';
	$new_product->unit = 'KG';
	$new_product->price = 450;
	$new_product->save();
	
Get a single product with id 54
	Fakturan\Model\Product(54);
	
Edit product

	$book = Fakturan\Model\Product::find(54);
	$book->price = 25;
	$book->save();
	
Delete a product

	$product_to_be_deleted = Fakturan\Model\Product::find(54);
	$product_to_be_deleted->destroy();
	