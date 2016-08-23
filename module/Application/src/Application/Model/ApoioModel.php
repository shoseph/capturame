<?php
namespace Application\Model;
use Application\Entity\Apoio;

use Zend\Mail\Message;

use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Transport\Smtp;
use Extended\Model\CapturaModel;

class ApoioModel extends CapturaModel
{ 
    public function enviarApoio(Apoio $apoio)
    {
    
    	$html = "<p>Empresa: <b>{$apoio->empresa}</b></p>
    	         <p>Email para contato: {$apoio->email}</p>
    	         <p>{$apoio->apoio}</p>";
    	
    	$mail = new Message();
    	$mail->addFrom(constant('NO-REPLY'), constant('NO-REPLY-NAME'))
    	->addTo('email@captura.me', 'Apoio')
    			->setSubject("[Apoio] de empresa: {$apoio->empresa}");
    
    			$bodyPart = new \Zend\Mime\Message();
    			$bodyMessage = new \Zend\Mime\Part($html);
    			$bodyMessage->type = 'text/html';
    	$bodyPart->setParts(array($bodyMessage));
    	$mail->setBody($bodyPart);
    	$mail->setEncoding('UTF-8');
    
    	$transport = new Smtp();
    	$options   = new SmtpOptions(array(
        	'name' => 'captura.me',
        	'host' => 'smtp.gmail.com',
        	'connection_class' => 'login',
        	'port' => '465',
        	'connection_config' => array(
            	'ssl' => 'ssl',
            	'username' => 'no-reply@captura.me',
            	'password' => 'r@f@123492268946',
    	    ),
    	));
    
    	$transport->setOptions($options);
    	$transport->send($mail);
    
    }
    
    /**
     * Método que salva um apoio
     * @param Apoio $apoio
     */
    public function salvarApoio(Apoio $apoio)
    {
    	return $this->getTable('apoio')->save($apoio);
    }
}
