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
	public function __construct($model, $collection)
	{
		$this->model = $model;
		$this->position = 0;
		
		foreach($collection['data'] as $item)
		{
			array_push($this->collection, new $model($item));
		}

		$this->pagination = json_decode(json_encode($collection['paging']));
		
	}
	
	#
	#
	#
	public function concat(Collection $collection)
	{
		foreach($collection as $item)
		{
			array_push($this->collection, $item);
		}
		return $this;
	}
	
	
	public function count()
	{
		return count($this->collection);
	}
	
	#
	#
	#
	public function totalPages()
	{
		return $this->pagination->total_pages;
	}
	
	#
	#
	#
	public function currentPage()
	{
		return $this->pagination->current_page;
	}
	
	#
	#
	#
	public function nextPage()
	{		
		if(!$this->pagination->next)
		{
			return null;
		}
		
		return call_user_func(get_class($this->model) . '::all', $this->parseQueryString($this->pagination->next));
	}
	
	#
	#
	#
	public function previousPage()
	{
		if(!$this->pagination->previous)
		{
			return null;
		}
		
		return call_user_func(get_class($this->model) . '::all', $this->parseQueryString($this->pagination->previous));
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
	
	#
	#
	#
	private function parseQueryString($string)
	{
		$queryString = substr($string, strrpos($string, '?') + 1);
		parse_str($queryString, $arr);
		return $arr;
	}

}
