<?php
namespace Application\Controller;
use Application\Entity\Apoio;

use Extended\Controller\CapturaController;
use Zend\View\Model\ViewModel;
class ApoioController extends CapturaController
{

    
    /**
     * Action que renderiza a pagina inicial
     */
    public function adicionarApoioAction ()
    {
        $form    = $this->getForm('adicionarApoio');
        $request = $this->getRequest();
        
        if($request->isPost())
        {
        	$form->setData($request->getPost()->toArray());
        	if ($form->isValid()) {
        	    $apoio = Apoio::getNewInstance()->fillinArray($form->getData());
        	    $this->getModel('apoio')->enviarApoio($apoio);
        	    $this->getModel('apoio')->salvarApoio($apoio);
       			$this->flashMessenger()->addMessage($this->getMsg('Apoio Cadastrado com sucesso!'));
                return $this->redirect()->toRoute('home');
        	}
        }
        return new ViewModel(array('form' => $form));
    }  

}
