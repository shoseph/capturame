<?php
namespace Application\View\Helper;

use User\Form\LoginForm;

use Extended\View\CapturaViewHelper;

class MsgHelper extends CapturaViewHelper
{

    public function __invoke ()
    {
        $config = array();
        $config['view'] = $this->getView();
        
        return $this->renderPartial('msg', $config, 'Application', 'Index');
    }
    
}