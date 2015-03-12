<?php
	
namespace Fakturan\Resources;

use Fakturan\Resources\Transport as Transport;

class Instance {
	
	#
	#
	#
	public static function fetch($model, $id, $params)
	{
		$response = Transport::instance()->get($model->getUri().'/'.$id);
		return static::factory($model, $response['data']);
	}	
	
	#
	#
	#
	public static function save($model)
	{
		$attributes = $model->attributes();
		
		if($model->persistent)
		{
			return Transport::instance()->put($model->getUri().'/'.$model->getIdentifier(), $attributes);
		}
		else
		{
			return Transport::instance()->post($model->getUri(), $attributes);
		}
	}
	
	#
	#
	#
	public static function destroy($model)
	{
		return Transport::instance()->delete($model->getUri().'/'.$modle->getIdentifier());
	}
	
	#
	#
	#
	public static function factory($model, $attributes)
	{
		$instance = new $model($attributes);
		$instance->persistent = true;
		return $instance;
	}	
	
}
