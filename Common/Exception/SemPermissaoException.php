<?php
namespace Common\Exception;

class SemPermissaoException extends \Exception
{
    public function __construct($message = null, $code = null, $previous = null)
    {
    	$message = $message  ? $message : 'Seu usuário não possui permissão para acessar esse item';
        parent::__construct($message, $code, $previous);
    }
}
