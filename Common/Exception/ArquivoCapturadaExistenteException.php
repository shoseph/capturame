<?php
namespace Common\Exception;

class ArquivoCapturadaExistenteException extends \Exception
{
    public function __construct($message = null, $code = null, $previous = null)
    {
    	$message = $message  ? $message : 'Arquivo da Capturada já está inserida no captura.me';
        parent::__construct($message, $code, $previous);
    }
}
