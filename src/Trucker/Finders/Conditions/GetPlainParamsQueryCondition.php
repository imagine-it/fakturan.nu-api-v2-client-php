<?php

/**
 * This file extends Trucker
 *
 * (c) Johan Tell <johan@imagineit.se>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Trucker\Finders\Conditions;

use Illuminate\Container\Container;
use Trucker\Facades\Config;

class GetPlainParamsQueryCondition implements QueryConditionInterface
{
	
	const PROPERTY = 'property';
	
	const VALUE = 'value';
	
  /**
   * The IoC Container
   *
   * @var Illuminate\Container\Container
   */
	protected $app;
	
	/**
	 * Colleciton of conditions
	 */
	protected $conditions = [];
	
	
  /**
   * Build a new GetArrayParamsQueryCondition
   *
   * @param Container $app
   */
  public function __construct(Container $app)
  {
  	$this->app = $app;
  }
  
  
  /**
   * Function to return a new popuplated instance,
   * typically this would be called from the Facade.
   * 
   * @return Trucker\Finders\Conditions\GetArrayParamsQueryCondition
   */
  public function newInstance()
  {
      $instance = new static($this->app);
      return $instance;
  }
  
  
  /**
	 * Add condition
	 */
	public function addCondition($property, $condition, $value)
	{
		
		if($condition !== '=') throw new \InvalidArgumentException("Invalid condition. Only allowing =");
		
		$this->condition[] = [
			self::PROPERTY => $property,
			self::VALUE => $value
		];
	}
	
	
    /**
   * Function to set the logical operator for the 
   * combination of any conditions that have been passed to the 
   * addCondition() function
   * 
   * @param string $operator
   * @return  void
   */
  public function setLogicalOperator($operator)
  {
    if ($operator != $this->getLogicalOperatorAnd() &&
      $operator != $this->getLogicalOperatorOr()
    ) {
      throw new \InvalidArgumentException("Invalid logical operator: {$operator}");
    }

    $this->logicalOperator = $operator;
  }


  /**
   * Function to get the string representing
   * the AND logical operator
   * 
   * @return string
   */
  public function getLogicalOperatorAnd()
  {
    return Config::get('query_condition.get_array_params.and_operator');
  }


  /**
   * Function to get the string representing
   * the OR logical operator
   * 
   * @return string
   */
  public function getLogicalOperatorOr()
  {
    return Config::get('query_condition.get_array_params.or_operator');
  }

	
	/**
	 *
	 */
	public function addToRequest(&$request)
	{
		$query = $request->getQuery();
		
		foreach($this->condition as $condition)
		{
			$query->add($condition[self::PROPERTY], $condition[self::VALUE]);
		}
	}
	
  /**
   * Function to convert the conditions and
   * logical operator represented in this class
   * to an array, this is useful for testing
   * 
   * @return array
   */
  public function toArray()
  {
    $params = [];

    foreach ($this->conditions as $condition) {
      $params[$condition[$condition[self::PROPERTY]]] = $condition[self::VALUE];
    }
    return $params;
  }


  /**
   * Function to convert the conditions and logical operator
   * represented in this class to a querystring, this is useful
   * for testing
   * 
   * @return string
   */
  public function toQueryString()
  {
    return http_build_query($this->toArray());
  }
}
