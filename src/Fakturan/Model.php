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
	 * Returns attributes as json
	 *
	 * @return json attributes as json
	 */	
	public function toJson()
	{
		return json_encode($this->attributes());
	}	
}