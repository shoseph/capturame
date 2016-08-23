<?php
namespace Common\Exception;

class PontoCadastradoException extends \Exception
{
    public function __construct($message = null, $code = null, $previous = null)
    {
    	$message = $message  ? $message : 'Você já votou nessa foto hoje, tente novamente amanhã.';
        parent::__construct($message, $code, $previous);
    }
}
