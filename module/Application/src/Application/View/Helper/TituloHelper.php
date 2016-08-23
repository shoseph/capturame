<?php
namespace Application\View\Helper;

use User\Form\LoginForm;

use Extended\View\CapturaViewHelper;

class TituloHelper extends CapturaViewHelper
{

    private $_titulo;
    
    public function __invoke ($titulo = null)
    {
        !$titulo ?: $this->setTitulo($titulo);
        return $this;
    }
    
    
    public function setTitulo($titulo)
    {
        !$titulo ?: $this->_titulo = $titulo;
    }
    
    public function html()
    {
        $config = array('titulo' => $this->_titulo);
        $config['view'] = $this->getView();
        
        return $this->renderPartial('titulo', $config, 'Application', 'Index');
    }
    
}