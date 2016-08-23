<?php
namespace Application\View\Helper;

use Extended\View\CapturaViewHelper;

class SliderHelper extends CapturaViewHelper
{
    protected $fotosRandomicas;
    public function __invoke ()
    {
        $retorno = array();
        $quantidade = 9;
        $this->fotosRandomicas = $this->getModel('Index', 'Capturada')->getRandomCapturadas($quantidade);
        return $this;
    }
    
    public function slidert2(){
        return $this->renderPartial('slider-t2', array('fotos' => $this->fotosRandomicas), 'Application', 'Index');
    }

    public function slidernr(){
        return $this->renderPartial('slider-nr', array('fotos' => $this->fotosRandomicas), 'Application', 'Index');
    }
     
}