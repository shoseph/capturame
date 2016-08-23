<?php
namespace Application\Model;
use Zend\Form\Form;
use User\Entity\User;
use Zend\Mail;
use Extended\Model\CapturaModel;

/**
 * Classe que atende aos modelos do programa do site externo.
 */
class IndexModel extends CapturaModel
{
    
    
    public function enviarSugestao(Form $form)
    {
        
        $dados = $form->getData();
        $html = "
        <p>Captura.me Evento: <b>{$dados['evento']}</b></p>
        <p>Email para contato: {$dados['email']}</p>
        <p>Aparecer no dia <b>{$dados['data']}</b> as <b>{$dados['hora']}</b></p>
        <p>{$dados['descricao']}</p>
        ";
        
        $mail = new Mail\Message();
        $mail->addFrom(constant('NO-REPLY'), constant('NO-REPLY-NAME'))
        ->addTo('sugestoes@captura.me', 'Sugestões')
        ->setSubject("[Captura.me] Sugestão {$dados['evento']}");
        
        $bodyPart = new \Zend\Mime\Message();
        $bodyMessage = new \Zend\Mime\Part($html);
        $bodyMessage->type = 'text/html';
        $bodyPart->setParts(array($bodyMessage));
        $mail->setBody($bodyPart);
        $mail->setEncoding('UTF-8');
        
        $transport = new Mail\Transport\Smtp();
        $options   = new Mail\Transport\SmtpOptions(array(
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
    
}
