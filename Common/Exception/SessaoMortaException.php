<?php
namespace Common\Exception;

class SessaoMortaException extends \Exception
{
    public function __construct($message = null, $code = null, $previous = null)
    {
    	$message = $message  ? $message : 'Você deslogou em algum momento, faça novamente o seu login.';
        parent::__construct($message, $code, $previous);
    }
}
