<?php
namespace Fakturan\Requests;	
		
use Fakturan\Fakturan;
use Fakturan\Error\AccessDenied;
use Fakturan\Error\ClientError;
use Fakturan\Error\ConnectionFailed;
use Fakturan\Error\ResourceNotFound;
use Fakturan\Error\ResourceInvalid;
use Fakturan\Error\ServerError;		
use Fakturan\Error\ValidationError;		
use Fakturan\Error\FakturanException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Ring\Exception\ConnectException;
		
class JsonRequest {
	
	private $type;
	private $uri;
	private $params;
	private $body;
	private $client;
	
	public function __construct($type, $uri, $params = [], $body = null)
	{
		$this->client = Fakturan::api_client();
		$this->type = $type;
		$this->uri = $uri;
		$this->params = $params;
		$this->body = $body;
	}
	
	
	public function send(){
		
		try {
			$request = $this->client->createRequest($this->type, $this->uri, [
				'json' => $this->body,
				'query' => $this->params,
				'exceptions' => true
			]);
			$response = $this->client->send($request)->json();
			return $response;
		}		
		catch(ParseException $e){
			echo 'ParseException';
		}
		catch(ClientException $e){
			$response = $e->getResponse();
						
			switch($response->getStatusCode())
			{
				case 401:
					throw new AccessDenied($e->getMessage());
					break;
				case 404:
					throw new ResourceNotFound($e->getMessage());
					break;
				case 407:
					throw new ConnectionFailed($e->getMessage());
					break;
				case 422:
					throw new ResourceInvalid($e->getMessage());
					break;
				default:
					throw new ClientError($$e->getMessage());
					break;
			}	
		}
		catch(ConnectException $e){
			throw new ConnectionFailed($e);
		}
		catch(ServerException $e){
			throw new ServerError($e->getMessage());
		}
		catch(TransferException $e)
		{
			throw new ConnectionFailed($e->getMessage());
		}
		catch(RequestException $e){ 
			throw new FakturanException($e); 
		}
		
	}
	
}