<?php

use Fakturan\Fakturan;	
use Fakturan\Model;	
use Fakturan\Product;
use VCR\VCR;

class TestModel extends Model { protected $uri = 'tests'; }
	
class ModelTest extends PHPUnit_Framework_TestCase
{

	protected static $model;
	
	public static function setUpBeforeClass()
	{
		Fakturan::setup('-VrmL6FGj6c61srVkM9H', 'bVSNkch6dam9R0-8OKwIGK1YRdbtefEYy-fFTDTJ', [
			'protocol' => 'http',
			'domain' => '0.0.0.0:3000'
		]);

		VCR::insertCassette('test_record');

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

	
	public function testErrorReturnsFalseAndAddsErrors()
	{
		$model = new TestModel(['name' => '']);		
		$this->assertEquals(false, $model->save());
		$this->assertSame(['name' => ["cannot be blank"]], $model->errors());
	}
	
	public function testCanUpdatePersistentItem()
	{
		$item = TestModel::find(1);
		$item->record = 'new';
		$this->assertEquals(true, $item->save());
		$this->assertEquals(['id' => 1, 'record' => 'new'], $item->attributes());
	}

/*
	public function testCanUpdatePersistentItem()
	{
		$item = Product::find(1);
		$item->name = 'new name';
		$this->assertEquals(true, $item->save());
		$this->assertEquals(['id' => 1, 'record' => 'new'], $item->attributes());
	}
*/

	
}