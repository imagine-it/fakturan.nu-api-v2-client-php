<?php

namespace Fakturan;
use GuzzleHttp\Client as Client;

class Fakturan {
  
  private static $username;
  private static $password;
  
  private static $options = [
	  'protocol' => 'https',
    'domain' => 'fakturan.nu',
    'uri' => 'api',
    'version' => 2,
    'sandbox' => false
  ];
  
  
  
  #
  #
  #
  public static function setup($username, $password, $options = [])
  {
	  self::$options = array_merge(self::$options, $options);
	  
	  self::$username = $username;
	  self::$password = $password;
  }
  
	public static function api_client()
	{ 
		return new Client([
			'base_url' => self::base_url(),			
			'defaults' => [
				'auth' => [self::$username, self::$password],
				'headers' => [			
	        'Accept' => 'application/json',
	        'Accept-Encoding' => 'utf-8'
				]
			]
		]); 
	}
	
	#
	#
	#
	public static function base_url()
	{
  	$url = self::$options['protocol'].'://';
  	
  	# Add sandbox to endpoint if we want to use sandbox
  	if(self::$options['sandbox']) $url .= 'sandbox.';	
  	
  	$url .= self::$options['domain'].'/'.self::$options['uri'].'/v'.self::$options['version'].'/';
  	
  	return $url; 	
	}
	
}