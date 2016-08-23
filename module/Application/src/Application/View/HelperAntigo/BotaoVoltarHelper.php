<?php
namespace Application\View\Helper;

use Extended\View\CapturaViewHelper;

class BotaoVoltarHelper extends CapturaViewHelper
{

    public function __invoke ()
    {
        return $this->renderPartial('voltar', null, 'Application', 'Index');
    }
    
}