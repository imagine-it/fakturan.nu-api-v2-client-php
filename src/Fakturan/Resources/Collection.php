<?php
	
namespace Fakturan\Resources;

use Fakturan\Resources\Transport as Transport;
use Fakturan\Resources\Instance as Instance;

class Collection implements \Iterator {
	
	private $collection = [];
	private $position = 0;
	
	#
	#
	#
	public function __construct($collection)
	{
		$this->collection = $collection;
		$this->position = 0;
	}
	
	#
	#
	#
	public static function fetch($model, $params = [])
	{
		$items = Transport::instance()->get($model->getUri(), $params);
		
		$collection = [];
		foreach($items['data'] as $item)
		{
			array_push($collection, Instance::factory($model, $item));
		}
				
		return new static($collection);
	}	
	
	#
	#
	#
	public function next_page()
	{
		
	}
	
	#
	#
	#
	public function previous_page()
	{
		
	}
	
	#
	#
	#	
	function rewind() {
		$this->position = 0;
	}
	
	#
	#
	#
	function current() {
		return $this->collection[$this->position];
	}
	
	#
	#
	#
	function key() {
		return $this->position;
	}
	
	#
	#
	#
	function next() {
		++$this->position;
	}
	
	#
	#
	#
	function valid() {
		return isset($this->collection[$this->position]);
	}

}
