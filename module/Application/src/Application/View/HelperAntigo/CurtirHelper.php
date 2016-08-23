<?php
namespace Application\View\Helper;

use Extended\View\CapturaViewHelper;

class CurtirHelper extends CapturaViewHelper
{
    public function __invoke ($url)
    {
        return $this->renderPartial('bloco-curtir', array('url' => $url), 'Application', 'Index');
    }
    
}