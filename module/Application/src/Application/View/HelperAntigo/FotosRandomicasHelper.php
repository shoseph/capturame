<?php
namespace Application\View\Helper;

use Extended\View\CapturaViewHelper;

class FotosRandomicasHelper extends CapturaViewHelper
{
    protected $fotosRandomicas;
    public function __invoke ()
    {
        $retorno = array();
        $quantidade = 5;
        $this->fotosRandomicas = $this->getModel('Index', 'Capturada')->getRandomCapturadas($quantidade);
        return $this;
    }
    
    public function html()
    {
        $resultado = '';
        foreach($this->fotosRandomicas as $key => $foto){
            $resultado .= $this->renderPartial('fotos-randomicas', array('foto' => $foto, 'numero' => $key + 1), 'Application', 'Index');
        }
        return $resultado;
    }
     
}