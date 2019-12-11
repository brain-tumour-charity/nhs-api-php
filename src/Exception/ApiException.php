<?php
namespace NHSAPI\Exception;

use Exception;

class ApiException extends Exception
{

    /**
     * @var string
     */
    private $parameters;
    /**
     * @var string
     */
    private $httpMethod;
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $errors;

    /**
     * CurrencyCloudException constructor.
     *
     * @param string $message
     * @param string $code
     * @param array $errors
     * @param Exception|null $previous
     */
    public function __construct(
        $message = '',
        $code    = 0,
        $errors  = [],
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    /**
     * @return string
     */
    public function getErrors()
    {
        return $this->errors;
    }

}
