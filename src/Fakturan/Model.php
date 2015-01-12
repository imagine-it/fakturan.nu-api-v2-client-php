<?php
	
namespace Fakturan;

use Trucker as Trucker;

class Model extends Trucker\Resource\Model {
	
  /**
   * Getter function to access the
   * underlying attributes array for the
   * entity 
   * 
   * @return arrayhttpStatusError
   */
  public function attributes()
  {
	  return array(strtolower($this->getResourceName()) => $this->properties);
  }
	
}