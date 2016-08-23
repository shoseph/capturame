<?php
namespace Application\View\Helper;

use User\Auth\CapturaAuth;

use User\Form\LoginForm;

use Extended\View\CapturaViewHelper;

class LoginHelper extends CapturaViewHelper
{

    public function __invoke ($usuarioLogado)
    {
        $config = array('usuarioLogado' => $usuarioLogado);
        
        if($usuarioLogado){
            $config['quantidadeCapturadas'] = $this->getModel('user','user')->getQuantidadeCapturadas(CapturaAuth::getInstance()->getUser()->id_usuario);
        }
        
        if($this->_session->offsetExists('form')){
            $form = new LoginForm();
            $form->setData($this->_session->offsetGet('form'));
            $config['form'] = $form;
        } else {
            $config['form'] = new \User\Form\LoginForm();
        }
        $config['view'] = $this->getView();
        
        return $this->renderPartial('login', $config, 'Application', 'Index');
    }
    
}