<?php
	
use Fakturan\Fakturan;
use Fakturan\Client;	
use Fakturan\Error\AccessDenied;
use Fakturan\Error\ConnectionFailed;
use Fakturan\Error\ResourceNotFound;
use Fakturan\Error\ServerError;		
use VCR\VCR;

	
class ExceptionTest extends PHPUnit_Framework_TestCase
{

	public static function tearDownAfterClass()
	{
		Fakturan::setup('-VrmL6FGj6c61srVkM9H', 'bVSNkch6dam9R0-8OKwIGK1YRdbtefEYy-fFTDTJ', [
			'protocol' => 'https',
			'domain' => 'fakturan.nu'
		]);
	}	
	
	public function setUp()
	{
    VCR::insertCassette('exception_requests');
		
		Fakturan::setup('-VrmL6FGj6c61srVkM9H', 'bVSNkch6dam9R0-8OKwIGK1YRdbtefEYy-fFTDTJ', [
			'protocol' => 'https',
			'domain' => 'fakturan.nu'
		]);
	}
	
	#
	# Actual tests
	#	


	public function testTrowsAccessDenied()
	{
		$this->setExpectedException('Fakturan\Error\AccessDenied');
		Fakturan::setup('-VrmL6FGj6c61srVkM9H-', 'bVSNkch6dam9R0-8OKwIGK1YRdbtefEYy-fFTDTJ');
		Client::all();
	}
		
	# As we cannot store fixtures to non-existent servers we point it at localhost:1
	public function testThrowsConnectionFailed()
	{		
    
		$this->setExpectedException('Fakturan\Error\ConnectionFailed');
		Fakturan::setup('username', 'password', [
			'domain' => 'localhost:1'
		]);
		
		$client = Client::find(1);
	}
	
	public function testDoesNotThrowResourceNotFoundOnModel()
	{
		$client = Client::find(999999999999999999);
		$this->assertEquals(NULL, $client);
	}
	
	public function testDoesNotThrowResourceInvalidOnModel()
	{
		$client = new Client(['name' => false]);
		$client->save();
		$this->assertEquals(true, (bool) $client->errors());
	}
	
	# Fixture includes an answer with 500-error
	public function testThrowsServerError()
	{
		$this->setExpectedException('Fakturan\Error\ServerError');
		$client = Client::find(99999999999999999999999999999);
	}

	
	
	
}