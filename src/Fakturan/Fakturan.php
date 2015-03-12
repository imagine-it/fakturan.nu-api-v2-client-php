<?php

namespace Fakturan;

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
	  self::$username = $username;
	  self::$password = $password;
	  self::$options = array_merge(self::$options, $options);
  }
  
  public static function username(){ return self::$username; }
  public static function password(){ return self::$password; }
	
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