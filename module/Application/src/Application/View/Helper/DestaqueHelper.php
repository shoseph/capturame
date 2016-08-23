<?php
namespace Application\View\Helper;

use User\Form\LoginForm;

use Extended\View\CapturaViewHelper;

class DestaqueHelper extends CapturaViewHelper
{

    public function __invoke ()
    {
        $config = array();
        $config['view'] = $this->getView();
        $config['capturadas'] = $this->getModel('Index', 'Capturada')->getNovasCapturadas(1, 11);
        return $this->renderPartial('destaque', $config, 'Application', 'Index');
    }
    
}