<?php
require 'vendor/autoload.php';	
use Trucker\Facades\Config as TruckerConfig;	

class Fakturan {

	public static function setup($username, $password)
	{
		TruckerConfig::set('auth.basic.username', $username);
		TruckerConfig::set('auth.basic.password', $password);
	}
	
}

require 'Fakturan/Model.php';	
require 'Fakturan/Models/Client.php';
require 'Fakturan/Models/Product.php';
