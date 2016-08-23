<?php
namespace Application\View\Helper;
use Extended\View\CapturaViewHelper;
class CamposRequeridosHelper extends CapturaViewHelper
{
    public function __invoke ()
    {
        return '<span style="display: block; margin: 10px;">São os campos requeridos <img src="/images/required_10.png"></span>';
    }
    
    
         
}