<?php
	
use Fakturan\Fakturan;	
	
class FakturanTest extends PHPUnit_Framework_TestCase
{
	
	public static function setUpBeforeClass()
	{
		Fakturan::setup('username', 'password', ['sandbox' => true]);
	} 
	
	public static function tearDownAfterClass()
	{
		Fakturan::setup('username', 'password', ['sandbox' => false]);
	}
	
	#
	# Actual tests
	#	

	public function testAllowChangingOfEndpoint()
	{
		$this->assertEquals('https://sandbox.fakturan.nu/api/v2/', Fakturan::base_url());
	}
	
	public function testCorrectlyReturnsInstanceOfClient()
	{
		$this->assertInstanceOf('GuzzleHttp\Client', Fakturan::api_client());
	}
	
}