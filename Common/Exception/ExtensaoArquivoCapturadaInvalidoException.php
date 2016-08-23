<?php
namespace Common\Exception;

class ExtensaoArquivoCapturadaInvalidoException extends \Exception
{
    public function __construct($message = null, $code = null, $previous = null)
    {
    	$message = $message  ? $message : 'Extensão do arquivo enviado não é permitida no captura.me';
        parent::__construct($message, $code, $previous);
    }
}
