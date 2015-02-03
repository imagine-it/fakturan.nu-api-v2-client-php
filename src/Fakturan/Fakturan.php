<?php

namespace Fakturan;
use Exception;
use Trucker\Facades\Config as TruckerConfig;	

class Fakturan {
  
  protected static $properties = [
    'protocol' => 'https',
    'domain' => 'fakturan.nu',
    'uri' => 'api',
    'version' => 2,
    'sandbox' => false
  ];
  
/*
  protected static $protocol = 'https';
  protected static $domain = 'fakturan.nu/api';
  protected static $version = 2;
  protected static $sandbox = false;
*/



  #
  #
  #
	public static function setup($username, $password)
	{
		TruckerConfig::set('auth.driver', 'basic');
		TruckerConfig::set('auth.basic.username', $username);
		TruckerConfig::set('auth.basic.password', $password);
		
		
		# Setup config for fakturan
		self::set_endpoint();
		TruckerConfig::set('request.http_method_param', '_method');
		
		# Setup for query conditions
		TruckerConfig::set('query_condition.driver', 'get_plain_params');

	}

	#
	#
	#
	public static function set($property, $value)
	{
    if(!isset(self::$properties[$property])) throw new Exception("The property `$property` is not a valid setting");
    
  	self::$properties[$property] = $value;  	
  	self::set_endpoint();
	}
	
	#
	#
	#
	public static function get($property)
	{
  	return self::$properties[$property];
	}
	
	#
	#
	#
	private static function set_endpoint()
	{
  	$url = self::$properties['protocol'].'://';
  	
  	# Add sandbox to endpoint if we want to use sandbox
  	if(self::$properties['sandbox']) $url .= 'sandbox.';	
  	
  	$url .= self::$properties['domain'].'/'.self::$properties['uri'].'/v'.self::$properties['version'];
  	
  	TruckerConfig::set('request.base_uri', $url);  	
	}
	
}