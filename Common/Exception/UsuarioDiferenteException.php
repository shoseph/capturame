<?php
namespace Common\Exception;

class UsuarioDiferenteException extends \Exception
{
    public function __construct($message = null, $code = null, $previous = null)
    {
    	$message = $message  ? $message : 'Esta ação não pode ser feita pelo seu usuário.';
        parent::__construct($message, $code, $previous);
    }
}
