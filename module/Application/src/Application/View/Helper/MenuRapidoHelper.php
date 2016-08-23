<?php
namespace Application\View\Helper;

use User\Auth\CapturaAuth;

use User\Form\LoginForm;

use Extended\View\CapturaViewHelper;

class MenuRapidoHelper extends CapturaViewHelper
{

    public function __invoke ($usuarioLogado)
    {
        $config = array('usuarioLogado' => $usuarioLogado);
        $config['view'] = $this->getView();
        
        return $this->renderPartial('menu-rapido', $config, 'Application', 'Index');
    }
    
}