<?php
namespace Application\Controller;
use User\Auth\CapturaAuth;
use Common\Exception\SemPermissaoException;
use Extended\Controller\CapturaController;
use Zend\View\Model\ViewModel;
use ZendGData;
class CalendarioController extends CapturaController
{

    
    /**
     * Action que renderiza a pagina inicial
     */
    public function indexAction ()
    {
        $retorno = array();
        return new ViewModel($retorno);
    }
        
        
        
//         $eventURL = $this->getModel()->getIdCapturaMe();
//         $service = $this->getModel()->getServiceGoogleAgenda();
        
//         $eventURL = "http://www.google.com/calendar/feeds/default/sugestoes%40captura.me";
// //         $listFeed= $service->getCalendarListFeed();
        
//         try {
//         	$events = $service->getCalendarEventEntry($eventURL);
//         } catch (ZendGData\App\Exception $e) {
//         	echo "Error: " . $e->getMessage();
//         }       
// //         $this->getModel()->novoEvento($service, $event);

        
//         echo "<ul>";
//         foreach ($events as $event) {
//         	echo "<li>" . $event->title . " (Event ID: " . $event->id . ")</li>";
//         }
//         echo "</ul>";
        
//         echo "<h1>Calendar List Feed</h1>";
//         echo "<ul>";
//         foreach ($listFeed as $calendar) {
//         	echo "<li>" . $calendar->id . "</li>";
//         }
//         echo "</ul>";
        
    
    
//     public function novoEventoAction()
//     {
//         try 
//        {
//             $eventURL = $this->getModel()->getIdCapturaMe();
//             $service = $this->getModel()->getServiceGoogleAgenda();
//         	$event = $service->getCalendarEventEntry($eventURL);
//         	$this->getModel()->novoEvento($service, $event);
        	
//         } catch (ZendGData\App\Exception $e) {
//         	throw new \Exception("Error: " . $e->getMessage());
//         }
//     }

}
