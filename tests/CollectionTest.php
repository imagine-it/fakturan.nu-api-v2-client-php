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
		$products = Product::all();
		$products = $products->nextPage()->concat($products);
		$this->assertEquals(60, $products->count());
	}
	
	public function testCanConcatPaginatedPagesInLoop()
	{
		$products = Product::all();
		$products->concat(Product::all(['page' => 2]));
		$this->assertEquals(60, $products->count());
	}
	
}