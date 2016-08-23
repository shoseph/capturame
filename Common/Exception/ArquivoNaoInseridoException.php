<?php
namespace Common\Exception;

class ArquivoNaoInseridoException extends \Exception
{
    public function __construct($message = null, $code = null, $previous = null)
    {
    	$message = $message  ? $message : 'Erro no envio da imagem, tente novamente!';
        parent::__construct($message, $code, $previous);
    }
}
