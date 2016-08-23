<?php
namespace Common\Exception;

class UsuarioInativoException extends \Exception
{
    public function __construct($message = null, $code = null, $previous = null)
    {
    	$message = $message  ? $message : 'Usuário Inatívo, por favor verifique seu email para sua ativação!';
        parent::__construct($message, $code, $previous);
    }
}
