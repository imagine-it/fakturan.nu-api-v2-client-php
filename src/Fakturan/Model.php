<?php
	
namespace Fakturan;

use Fakturan\Resources\Collection;
use Fakturan\Resources\Instance;
use Fakturan\Requests\JsonRequest;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Ring\Exception\ConnectException;

class Model {
	
	protected $uri = null;
	protected $primary_key = 'id';

	private $attributes = [];
	private $errors = [];
	
	public $persistent = false;
	 
	/**
	 *
	 */
	public function __construct($attributes = [])
	{
		$this->updateAttributes($attributes);
	}
	
	public function __set($property, $value) { $this->attributes[$property] = $value; }
	public function __get($property) { return array_key_exists($property, $this->attributes) ? $this->attributes[$property] : null; }

	
	/**
	 *
	 */	
	public static function find($id, $params = [])
	{
    return self::fetch($id, $params);
	}
	
	/**
	 * 
	 */
	public static function all($params = [])
	{
		return self::fetch(null, $params);
	}
	
	/**
	 *
	 */
	public function save()
	{
		if($this->persistent)
		{
			return $this->commit('update', $this->attributes());
		}
		else
		{
			return $this->commit('create', $this->attributes());
		}	
	}
	
	/**
	 *
	 */
	public function destroy()
	{
		return $this->commit('destroy');
	}
	
	/**
	 *
	 */
	public function errors()
	{
		return $this->errors;
	}
	
	#
	#
	#
	public function attributes()
	{
		return $this->attributes;
	}
	
	#
	#
	#
	public function updateAttributes($attributes)
	{
		$this->attributes = $attributes;
		return $this;
	}
		
	#
	#
	#
	public function getUri($id = null)
	{		
		if($id)
		{
			return $this->uri.'/'.$id;
		}

		return $this->uri;
	}
	
	#
	#
	#
	public function getIdentifier(){
		return $this->attributes[$this->primary_key];
	}
	
	#
	#
	#
	public function resourceName()
	{
		return $this->uri;
	}
	 
	/**
	 * Returns attributes as json
	 *
	 * @return json attributes as json
	 */	
	public function toJson()
	{
		return json_encode($this->attributes());
	}	
	
	#
	#
	#
	private static function fetch($id = null, $params = [])
	{
		try {
			$model = new static();			
			$response = self::sendRequest('GET', $model->getUri($id), $params);
			
			if($id)
			{	
				$model->persistent = true;
				return $model->updateAttributes($response['data']);
			}
			else
			{
				return new Collection($model, $response);
			}			
		}
		catch(RequestException $e)
		{
			throw $e;
		}
	}
	
	#
	#
	#
	private function commit($action)
	{
		try 
		{
			if($action == 'create')
			{
				$response = self::sendRequest('POST', $this->getUri(), null, $this->attributes());
			}
			else if($action == 'update')
			{
				$response = self::sendRequest('PUT', $this->getUri($this->id), null, $this->attributes());
			}
			else if($action == 'destroy')
			{
				$response = self::sendRequest('DELETE', $this->getUri($this->id));
			}
			
			if($action !== 'destroy' AND !$this->persistent)
			{								
				$this->attributes = $response['data'];
				$this->persistent = true;
			}			
			return true;
		}
		catch(RequestException $e)
		{
			if($e->hasResponse())
			{
				$this->errors = $e->getResponse()->json()['errors'];
			}
			return false;
		}
	}
	
	#
	#
	#
	private static function sendRequest($type, $uri, $url_params = [], $body = null)
	{		
		$request = new JsonRequest($type, $uri, $url_params, $body);		
		return $request->send();
	}
}