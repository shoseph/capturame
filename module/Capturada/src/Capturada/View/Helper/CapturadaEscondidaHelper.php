<?php
namespace Capturada\View\Helper;
use Extended\View\CapturaViewHelper;

class CapturadaEscondidaHelper extends CapturaViewHelper
{
    
    private $_urlCapturadaEscondida;
    
    public function __invoke ($urlCapturadaEscondida = null)
    {
    	!$urlCapturadaEscondida ?: $this->setUrlCapturadaEscondida($urlCapturadaEscondida);
    	return $this;
    }
    
    
    public function setUrlCapturadaEscondida($urlCapturadaEscondida)
    {
    	!$urlCapturadaEscondida ?: $this->_urlCapturadaEscondida = $urlCapturadaEscondida;
    }
    
    public function html()
    {
    	$config = array('urlCapturadaEscondida' => $this->_urlCapturadaEscondida);
    	$config['view'] = $this->getView();
     	return $this->renderPartial('capturada-escondida', $config, 'Capturada', 'index');
     	
    }
    
}