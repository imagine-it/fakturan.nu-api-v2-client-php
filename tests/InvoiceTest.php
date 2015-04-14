<?php

use Fakturan\Fakturan;	
use Fakturan\Invoice;
use Fakturan\Product;
use VCR\VCR;
	
class InvoiceTest extends PHPUnit_Framework_TestCase
{

	protected static $invoice_defaults;
	
	public static function setUpBeforeClass()
	{	
		self::$invoice_defaults = ['date' => '2015-01-01'];
		
		VCR::insertCassette('invoice_requests');
		
		Fakturan::setup('-VrmL6FGj6c61srVkM9H', 'bVSNkch6dam9R0-8OKwIGK1YRdbtefEYy-fFTDTJ', [
			'protocol' => 'http',
			'domain' => '0.0.0.0:3000'
		]);
	} 
	

	#
	# Actual tests
	#
	

	public function testCanAddRowsFromProductWithOverrides()
	{
		$invoice = new Invoice(self::$invoice_defaults);
		
		$product = Product::find(31);
		
		$invoice->addRow($product, ['amount' => 5, 'product_price' => '50.0']);
		
		$expected = [
			'product_id' => 31,
			'product_code' => '',			
			'product_name' => '.com domän', 
			'product_unit' => 'år', 
			'product_price' => '50.0',
			'product_tax' => 25,
			'amount' => 5
		];
		
		$this->assertEquals([$expected], $invoice->rows);
	}
	
	
	public function testCanReplaceRowsWithArray()
	{
		$invoice = new Invoice(self::$invoice_defaults);
		
		$expected = [
			'product_id' => 31,
			'product_code' => '',			
			'product_name' => '.com domän', 
			'product_unit' => 'år', 
			'product_price' => '49.0',
			'product_tax' => 25,
			'amount' => 5
		];
		
		$invoice->rows = [$expected];
			
		$this->assertEquals([$expected], $invoice->rows);
	}
	
	public function testCanAddRowsFromArray()
	{
		$invoice = new Invoice(self::$invoice_defaults);
		
		$expected = [
			'product_id' => 31,
			'product_code' => '',			
			'product_name' => '.com domän', 
			'product_unit' => 'år', 
			'product_price' => '49.0',
			'product_tax' => 25,
			'amount' => 5
		];
		
		$invoice->addRow($expected);
			
		$this->assertEquals([$expected], $invoice->rows);

	}
	
	public function testCanSaveInvoiceWithMultipleRows()
	{
		$invoice = new Invoice(self::$invoice_defaults);
		$invoice->client_id = 2;

		$product = Product::find(31);
		
		$invoice->addRow($product, ['amount' => '5', 'product_price' => '50.0']);
		$invoice->addRow($product, ['amount' => '5', 'product_price' => '51.0']);
		$invoice->addRow($product, ['amount' => '5', 'product_price' => '52.0']);
			
		$this->assertEquals(true, $invoice->save());
	}
	
}
