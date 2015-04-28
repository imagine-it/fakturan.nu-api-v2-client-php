<?php

use Fakturan\Fakturan;
use Fakturan\Product;
use VCR\VCR;
	
class CollectionTest extends PHPUnit_Framework_TestCase
{

	public static function setUpBeforeClass()
	{
		VCR::insertCassette('collection_request');
	}


	public function testCanConcatPaginatedPages()
	{
		$products = Product::all(['per_page' => 15]);
		$products = $products->nextPage()->concat($products);
		$this->assertEquals(30, $products->count());
	}
	
	public function testCanConcatPaginatedPagesInLoop()
	{
		$products = Product::all(['per_page' => 15]);
		$products->concat(Product::all(['page' => 2, 'per_page' => 15]));
		$this->assertEquals(30, $products->count());
	}
	
}