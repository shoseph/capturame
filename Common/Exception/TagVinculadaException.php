<?php
namespace Common\Exception;

class TagVinculadaException extends \Exception
{
    public function __construct($message = null, $code = null, $previous = null)
    {
    	$message = $message  ? $message : 'Capturada já possui esta tag!';
        parent::__construct($message, $code, $previous);
    }
}
