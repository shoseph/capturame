<?php
namespace Common\Exception;

class UsuarioInvalidoException extends \Exception
{
    public function __construct($message = null, $code = null, $previous = null)
    {
    	$message = $message  ? $message : 'Por favor corriga seu login ou senha, pois existe um incorreto';
        parent::__construct($message, $code, $previous);
    }
}
