<?php

namespace Fakturan;

use Trucker\Facades\Config as TruckerConfig;	

class Fakturan {

	public static function setup($username, $password)
	{
		TruckerConfig::set('auth.driver', 'basic');
		TruckerConfig::set('auth.basic.username', $username);
		TruckerConfig::set('auth.basic.password', $password);
		
		
		# Setup config for fakturan
		TruckerConfig::set('request.base_uri', 'http://0.0.0.0:3000/api/v2');
		TruckerConfig::set('request.http_method_param', '_method');
		
		# Setup for query conditions
		TruckerConfig::set('query_condition.driver', 'get_plain_params');

	}
	
}