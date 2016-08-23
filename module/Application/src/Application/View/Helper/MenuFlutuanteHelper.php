<?php
namespace Application\View\Helper;

use Extended\View\CapturaViewHelper;

class MenuFlutuanteHelper extends CapturaViewHelper
{

    public function __invoke ()
    {
        $config = array();
        $config['view'] = $this->getView();
        return $this->renderPartial('menu-flutuante', $config, 'Application', 'Index');
    }
    
}