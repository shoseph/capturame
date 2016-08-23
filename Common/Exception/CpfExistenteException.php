<?php
namespace Common\Exception;

class CpfExistenteException extends \Exception
{
    public function __construct($message = null, $code = null, $previous = null)
    {
    	$message = $message  ? $message : 'Por favor digite outro CPF, pois o mesmo já existe no Captura.Me';
        parent::__construct($message, $code, $previous);
    }
}
