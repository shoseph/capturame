<?php
namespace Application\View\Helper;
use Extended\View\CapturaViewHelper;
class CamposRequeridosHelper extends CapturaViewHelper
{
    public function __invoke ()
    {
        return '<span>São os campos requeridos <img src="/images/captura2/required_10.png"></span>';
    }
    
    
         
}