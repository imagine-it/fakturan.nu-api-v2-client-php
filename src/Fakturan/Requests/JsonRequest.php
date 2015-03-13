<?php
namespace Fakturan\Requests;	
		
use Fakturan\Fakturan;		
		
class JsonRequest {
	
	private $type;
	private $uri;
	private $params;
	private $body;
	private $client;
	
	public function __construct($type, $uri, $params = [], $body = null)
	{
		$this->client = Fakturan::client();
		$this->type = $type;
		$this->uri = $uri;
		$this->params = $params;
		$this->body = $body;
	}
	
	
	public function send(){
		$request = $this->client->createRequest($this->type, $this->uri, [
			'json' => $this->body,
			'query' => $this->params
		]);
		
		return $this->client->send($request)->json();
	}
	
}