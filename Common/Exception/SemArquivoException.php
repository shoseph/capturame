<?php
namespace Common\Exception;

class SemArquivoException extends \Exception
{
    public function __construct($message = null, $code = null, $previous = null)
    {
    	$message = $message  ? $message : 'Não possui um arquivo selecionado!';
        parent::__construct($message, $code, $previous);
    }
}
