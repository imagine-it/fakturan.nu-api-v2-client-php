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
		Fakturan::setup('-VrmL6FGj6c61srVkM9H', 'bVSNkch6dam9R0-8OKwIGK1YRdbtefEYy-fFTDTJ', [
			'protocol' => 'http',
			'domain' => '0.0.0.0:3000',
			'sandbox' => false
		]);
	}
	
	#
	# Actual tests
	#	

	public function testAllowChangingOfEndpoint()
	{
		$this->assertEquals('http://sandbox.0.0.0.0:3000/api/v2/', Fakturan::base_url());
	}
	
	public function testCorrectlyReturnsInstanceOfClient()
	{
		$this->assertInstanceOf('GuzzleHttp\Client', Fakturan::api_client());
	}
	
}