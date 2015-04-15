<?php

use Fakturan\Fakturan;	
use Fakturan\Model;	
use Fakturan\Product;
use VCR\VCR;

class TestModel extends Model { protected $uri = 'products'; }
	
class ModelTest extends PHPUnit_Framework_TestCase
{

	protected static $model;
	
	public static function setUpBeforeClass()
	{		
		VCR::insertCassette('base_model_requests');
		Fakturan::setup('-VrmL6FGj6c61srVkM9H', 'bVSNkch6dam9R0-8OKwIGK1YRdbtefEYy-fFTDTJ', [
			'protocol' => 'http',
			'domain' => '0.0.0.0:3000'
		]);
	} 

	
	
	#
	# Actual tests
	#

	public function testNewInstanceIsNotPersistent()
	{
		$model = new Model();
		$this->assertEquals(false, $model->persistent);
	}
	
	public function testSettingOfAttributes()
	{
		$model = new Model();
		$model->attribute = 'this attribute is now set';
		$this->assertEquals('this attribute is now set', $model->attribute);
	}
	
	public function testFindSingleRecord()
	{
		$record = TestModel::find(1);
		
		$this->assertEquals(['id' => 1, 'record' => 'found'], $record->attributes());
	}
	
	public function testFindRecordCollection()
	{
		$collection = TestModel::all();
		
		$this->assertInstanceOf('Fakturan\Resources\Collection', $collection);
	}
	
	public function testCanIterateCollection()
	{
		$collection = TestModel::all();
		
		$i = 0;
		foreach($collection as $item)
		{
			$i++;
		}
		$this->assertEquals(3, $i);
		$this->assertEquals(3, $collection->count());
	}
	
	public function testReturnTrueOnSave()
	{
		$model = new TestModel(['name' => 'test']);		
		$this->assertEquals(true, $model->save());
	}
	
	public function testUpdateAttributes()
	{
		$model = TestModel::find(76841);
		$model->updateAttributes(['name' => 'changed name']);
		$this->assertEquals(['id' => 76841, 'name' => 'changed name'], $model->attributes(['id', 'name']));
	}


	public function testErrorReturnsFalseAndAddsErrors()
	{
		$model = new TestModel(['name' => '']);		
		$this->assertEquals(false, $model->save());
		$this->assertSame(['name' => [['error' => "blank"]]], $model->errors());
	}
	
	public function testCanUpdatePersistentItem()
	{
		$item = TestModel::find(31);
		$item->record = 'new';
		$this->assertEquals(true, $item->save());
		$this->assertEquals(['id' => 31, 'record' => 'new'], ['id' => $item->id, 'record' => $item->record]);
	}	
	

	public function testCanDestroyItem()
	{
		$item = TestModel::find(76840);
		$this->assertEquals(true, $item->destroy());
	}
}