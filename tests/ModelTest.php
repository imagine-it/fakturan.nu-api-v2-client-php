<?php

use Fakturan\Fakturan;	
use Fakturan\Model;	
use Fakturan\Client;
use Fakturan\Product;
use VCR\VCR;

class TestModel extends Model { protected $uri = 'products'; }
	
class ModelTest extends PHPUnit_Framework_TestCase
{

	protected static $model;
	protected static $createdModelId;
	
	public static function setUpBeforeClass()
	{		
		VCR::insertCassette('base_model_requests');
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
		$record = TestModel::find(2);
		
		$this->assertEquals(['id' => 2], $record->attributes(['id']));
	}
	
	public function testFindRecordCollection()
	{
		$collection = TestModel::all();
		
		$this->assertInstanceOf('Fakturan\Resources\Collection', $collection);
	}
	
	public function testFindSingleRecordWithFindBy()
	{
		$record = Client::findBy(['number' => 2]);
		
		$this->assertEquals(2, $record->number);
	}
	
	public function testCanIterateCollection()
	{
		$collection = TestModel::all();
		
		$i = 0;
		foreach($collection as $item)
		{
			$i++;
		}
		
		$this->assertEquals(30, $i);
		$this->assertEquals(30, $collection->count());
	}
	
	public function testReturnTrueOnSave()
	{
		$model = new TestModel(['name' => 'A simple product', 'price' => 200]);		
		$this->assertEquals(true, $model->save());
		self::$createdModelId = $model->id;
	}
	
	public function testUpdateAttributes()
	{
		$model = TestModel::find(2);
		$model->updateAttributes(['name' => 'changed name']);
		$this->assertEquals(['id' => 2, 'name' => 'changed name'], $model->attributes(['id', 'name']));
	}


	public function testErrorReturnsFalseAndAddsErrors()
	{
		$model = new TestModel(['name' => '']);		
		$this->assertEquals(false, $model->save());
		$this->assertSame(['name' => [['error' => "blank"]]], $model->errors());
	}
	
	public function testCanUpdatePersistentItem()
	{
		$item = TestModel::find(2);
		$this->assertEquals(true, $item->save());
		$this->assertEquals(['id' => 2, 'name' => 'A simple product'], ['id' => $item->id, 'name' => $item->name]);
	}	
	

	public function testCanDestroyItem()
	{
		$item = TestModel::find(self::$createdModelId);
		$this->assertEquals(true, $item->destroy());
	}
}