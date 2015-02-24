<?php
	
namespace Fakturan;

use Trucker;
use Trucker\Facades\UrlGenerator;
use Trucker\Facades\Config;
use Trucker\Facades\RequestFactory;
use Trucker\Facades\ResponseInterpreterFactory;
use Trucker\Facades\ErrorHandlerFactory;
use Trucker\Facades\AuthFactory;

class Model extends Trucker\Resource\Model {

	/**
	 * Function to handle persistance of the entity across the
	 * remote API.	Function will handle either a CREATE or UPDATE
	 *
	 * @return Boolean	Success of the save operation
	 */
	public function save()
	{
		$this->attributes_in_scope = true;
		
		$result = parent::save();
		
		$this->attributes_in_scope = false;
		 
		return $result;
	 
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
}