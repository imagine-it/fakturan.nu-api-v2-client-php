<?php
namespace Fakturan;

class Product extends Model
{
	protected $uri = 'products';
	
	
	
	#
	#
	#
	public function toRow($overrides = [])
	{
		$product = [
			'product_id' => $this->id,
			'product_code' => $this->product_code,			
			'product_name' => $this->name, 
			'product_unit' => $this->unit, 
			'product_price' => $this->price,
			'product_tax' => $this->tax
		];
		
		return array_merge($product, $overrides);
	}
}
