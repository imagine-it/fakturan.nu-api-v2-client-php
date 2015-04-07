<?php
namespace Fakturan;

class Invoice extends Model
{
	protected $uri = 'invoices';
		
	#
	#
	#
	public function addRow($product, $overrides = [])
	{
		
		if(!is_array($product)){
			$product = $product->toRow($overrides);
		}
		
		$rows = is_array($this->rows) ? $this->rows : [];		
		array_push($rows, $product);		
		$this->rows = $rows;		
		return $this;
	}
}
