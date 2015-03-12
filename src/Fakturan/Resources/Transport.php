<?php
	
namespace Fakturan\Resources;

use Fakturan\Fakturan as Fakturan;
use GuzzleHttp\Client as Client;

class Transport {
	
	private $client;
	
	private static $instance = null;
	
	#
	#
	#
	public function __construct()
	{
		$base_url = Fakturan::base_url();
		$this->client = new Client([
			'base_url' => $base_url,
			'headers' => [			
        'Accept' => 'application/json'
			],
			'defaults' => [
				'auth' => [Fakturan::username(), Fakturan::password()]
			]
		]);
	}	
	
	#
	#
	#
	public static function instance()
	{
		if(self::$instance == null)
		{
			self::$instance = new static;
		}
		
		return self::$instance;
	}
	
	
	#
	#
	#
	public function get($url, $params = [])
	{
		return $this->createRequest('GET', $url, $params);
	}
	
	#
	#
	#
	public function post($url, $params = [])
	{
		return $this->createRequest('POST', $url, [], $params);
	}
	
	#
	#
	#
	public function put($url, $params = [])
	{
		return $this->createRequest('PUT', $url, [], $params);
	}
	
	#
	#
	#
	public function delete($url, $params = [])
	{
		return $this->createRequest('DELETE', $url, $params);
	}
	
	#
	#
	#
	private function createRequest($type, $url, $url_params, $body = null)
	{
		$request = $this->client->createRequest($type, $url, [
			'json' => $body,
			'query' => $url_params
		]);
		
		$response = $this->client->send($request);
		return $response->json();
	}
	
}
