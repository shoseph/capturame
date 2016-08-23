<?php
namespace Application\Model;
use Application\Entity\Calendario;

use Zend\Form\Form;
use User\Entity\User;
use Zend\Mail;
use Extended\Model\CapturaModel;
use ZendGData;
/**
 * Classe que atende aos modelos do programa do site externo.
 */
class CalendarioModel extends CapturaModel
{
    

    /**
     * Método que retorna os calendários
     * @return ZendGData\Calendar calendário
     */
    public function getServiceGoogleAgenda()
    {
        $service = ZendGData\Calendar::AUTH_SERVICE_NAME;
        $adapter = new \Zend\Http\Client\Adapter\Curl();
        $httpClient = new ZendGData\HttpClient();
        $httpClient->setAdapter($adapter);
        
        $login = "sugestoes@captura.me";
        $password = "r@f@123492268946";
        
        // Create an authenticated HTTP client
        $client = ZendGData\ClientLogin::getHttpClient($login, $password, $service, $httpClient);
        
        // Create an instance of the Calendar service
        $service = new ZendGData\Calendar($client);
        return $service;
    }
    
    /**
     * Método que retorna a id da lista chamada captura.me
     * @return string
     */
    public function getIdCapturaMe(){
        return 'http://www.google.com/calendar/feeds/default/sugestoes%40captura.me';
    }
    
    /**
     * Método que retorna um calendário a partir de um formulário
     * 
     * @param Form $form
     */
    public function getCalendarioByForm(Form $form)
    {
        $cal = $form->getData();
        $calendario = new Calendario();
        $calendario->titulo = $cal['evento']; 
        $calendario->descricao = $cal['descricao']; 
        $calendario->dtInicio = $cal['data']; 
        $calendario->hrInicio = $cal['hora']; 
        $calendario->email = $cal['email'];

        return $calendario;
    }
    
    /**
     * Método que insere um evento a partir de um objeto calendário.
     * 
     * @param Calendario $calendario
     */
    public function inserirEventoCalendario(Calendario $calendario, $event, $service)
    {
        $event->title = $service->newTitle($calendario->titulo); 
        $event->where = array($service->newWhere("Brasil"));
        $event->content = $service->newContent(
            $calendario->descricao . '<br /> <b>Enviado por</b>: ' . $calendario->email
        );
        $event->when = array($calendario->getWhen($service));
        return $service->insertEvent($event);
    }
}