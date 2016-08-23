<?php
namespace Common\Exception;

class LoginExistenteException extends \Exception
{
    public function __construct($message = null, $code = null, $previous = null)
    {
    	$message = $message  ? $message : 'Por favor digite outro login, pois o mesmo já existe no Captura.Me';
        parent::__construct($message, $code, $previous);
    }
}
