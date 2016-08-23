<?php
namespace Common\Exception;

class CapturadaExistenteException extends \Exception
{
    public function __construct($message = null, $code = null, $previous = null)
    {
    	$message = $message  ? $message : 'Capturada já está inserida no captura.me';
        parent::__construct($message, $code, $previous);
    }
}
