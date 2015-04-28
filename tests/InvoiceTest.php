<?php

use Fakturan\Fakturan;	
use Fakturan\Invoice;
use Fakturan\Product;
use VCR\VCR;
	
class InvoiceTest extends PHPUnit_Framework_TestCase
{

	protected static $invoice_defaults;
	
	protected static $product_defaults;
	
	public static function setUpBeforeClass()
	{	
		self::$invoice_defaults = ['date' => '2015-01-01'];
		
		self::$product_defaults = [
			'product_id' => 2,
			'product_code' => '',			
			'product_name' => 'A simple product', 
			'product_unit' => 'ST', 
			'product_price' => '200.0',
			'product_tax' => 25,
			'amount' => 5
		];		
		
		VCR::insertCassette('invoice_requests');
	} 
	

	#
	# Actual tests
	#
	

	public function testCanAddRowsFromProductWithOverrides()
	{
		$invoice = new Invoice(self::$invoice_defaults);
		
		$product = Product::find(2);
		
		$invoice->addRow($product, ['amount' => 5, 'product_price' => '200.0']);
		
		$this->assertEquals([self::$product_defaults], $invoice->rows);
	}
	
	
	public function testCanReplaceRowsWithArray()
	{
		$invoice = new Invoice(self::$invoice_defaults);
		
		$invoice->rows = [self::$product_defaults];
			
		$this->assertEquals([self::$product_defaults], $invoice->rows);
	}
	
	public function testCanAddRowsFromArray()
	{
		$invoice = new Invoice(self::$invoice_defaults);
		
		$invoice->addRow(self::$product_defaults);
			
		$this->assertEquals([self::$product_defaults], $invoice->rows);

	}
	
	public function testCanSaveInvoiceWithMultipleRows()
	{
		$invoice = new Invoice(self::$invoice_defaults);
		$invoice->client_id = 11;

		$product = Product::find(2);
		
		$invoice->addRow($product, ['amount' => 5, 'product_price' => '50.0']);
		$invoice->addRow($product, ['amount' => 5, 'product_price' => '51.0']);
		$invoice->addRow($product, ['amount' => 5, 'product_price' => '52.0']);
			
		$this->assertEquals(true, $invoice->save());
	}
	
}
