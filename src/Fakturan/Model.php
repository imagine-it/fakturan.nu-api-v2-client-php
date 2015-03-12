<?php
	
namespace Fakturan;

use Fakturan\Resources\Collection;
use Fakturan\Resources\Instance;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Ring\Exception\ConnectException;


class Model {
	
	protected $uri = '';
	protected $primary_key = 'id';

	private $attributes = [];
	private $errors = [];
	
	public $persistent = false;
	 
	/**
	 *
	 */
	public function __construct($attributes = [])
	{
		$this->attributes = $attributes;
	}
	
	public function __set($property, $value) { $this->attributes[$property] = $value; }
	public function __get($property) { return array_key_exists($property, $this->attributes) ? $this->attributes[$property] : null; }

	
	/**
	 *
	 */	
	public static function find($key, $params = [])
	{
		$model = new static;
    return Instance::fetch($model, $key, $params);

	}
	
	/**
	 * 
	 */
	public static function all($params = [])
	{
		$model = new static;
		return Collection::fetch($model, $params);
	}
	
	/**
	 *
	 */
	public function save()
	{
		return $this->query('save');		
	}
	
	/**
	 *
	 */
	public function destroy()
	{
		return $this->query('destroy');
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
	public function getUri()
	{
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
	private function query($type)
	{
		try 
		{
			$response = call_user_func("Fakturan\Resources\Instance::$type", $this);	
			if(!$this->persistent)
			{
				$this->attributes = $response['data'];
				$this->persistent = true;
			}			
			return true;
		}
		catch(RequestException $e)
		{
			$this->errors = $e->getResponse()->json()['error'];
			return false;
		}
	}
}