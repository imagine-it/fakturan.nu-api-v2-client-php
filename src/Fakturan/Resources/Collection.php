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
		if($this->totalPages() > $this->currentPage())
		{
			return self::fetch($this->model, ['page' => $this->currentPage() + 1]);
		}
		return null;
	}
	
	#
	#
	#
	public function previousPage()
	{
		if($this->currentPage !== 1 AND $this->totalPages() < $this->currentPage())
		{
			return self::fetch($this->model, ['page' => $this->currentPage() - 1]);
		}
		return null;
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
