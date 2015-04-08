<?php
	
namespace Fakturan\Error;

use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;

class ResourceInvalid extends FakturanException {
	
	public function __construct($message, RequestInterface $request, ResponseInterface $response = null, \Exception $previous = null)
	{
		// Set the code of the exception if the response is set and not future.
    $code = $response && !($response instanceof FutureInterface)
      ? $response->getStatusCode()
      : 0;
    parent::__construct($message, $code, $previous);
    $this->request = $request;
    $this->response = $response;
	}
	
	
  /**
   * Get the request that caused the exception
   *
   * @return RequestInterface
   */
  public function getRequest()
  {
    return $this->request;
  }

  /**
   * Get the associated response
   *
   * @return ResponseInterface|null
   */
  public function getResponse()
  {
    return $this->response;
  }

  /**
   * Check if a response was received
   *
   * @return bool
   */
  public function hasResponse()
  {
    return $this->response !== null;
  }	
}