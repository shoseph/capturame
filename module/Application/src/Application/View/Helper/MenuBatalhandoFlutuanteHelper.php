<?php
namespace Application\View\Helper;

use Extended\View\CapturaViewHelper;

class MenuBatalhandoFlutuanteHelper extends CapturaViewHelper
{

    public function __invoke ()
    {
        $config = array();
        $config['view'] = $this->getView();
        return $this->renderPartial('menu-batalhando-flutuante', $config, 'Application', 'Index');
    }
    
}