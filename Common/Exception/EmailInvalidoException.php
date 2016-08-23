<?php
namespace Common\Exception;

class EmailInvalidoException extends \Exception
{
    public function __construct($message = null, $code = null, $previous = null)
    {
    	$message = $message  ? $message : 'Este email não é válido no Captura.Me.';
        parent::__construct($message, $code, $previous);
    }
}
