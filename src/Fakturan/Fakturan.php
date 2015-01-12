<?php

namespace Fakturan;

use Trucker\Facades\Config as TruckerConfig;	

class Fakturan {

	public static function setup($username, $password)
	{
		TruckerConfig::set('auth.basic.username', $username);
		TruckerConfig::set('auth.basic.password', $password);
	}
	
}