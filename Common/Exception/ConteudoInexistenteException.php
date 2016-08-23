<?php
namespace Common\Exception;

class ConteudoInexistenteException extends \Exception
{
    public function __construct($message = null, $code = null, $previous = null)
    {
    	$message = $message  ? $message : 'Este conteúdo não existe mais.';
        parent::__construct($message, $code, $previous);
    }
}
