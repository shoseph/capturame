<?php
namespace Capturada\Controller;
use Common\Exception\SessaoMortaException;

use Facebook\src\FacebookApiException;

use Facebook\src\Facebook;

use Zend\Stdlib\Parameters;

use Zend\Http\Request;
use Common\Exception\TagVinculadaException;
use Common\Exception\SemPermissaoException;
use Capturada\Model\IndexModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Extended\Controller\CapturaController;
use Common\Exception\CapturadaExistenteException;
use Common\Exception\ArquivoCapturadaExistenteException;
use Common\Exception\ExtensaoArquivoCapturadaInvalidoException;
use \User\Auth\CapturaAuth;

class EventoController extends CapturaController
{
        
    /**
     * Action que mostra os eventos do Captura.Me
     */
    public function listarEventosAction()
    {
        $retorno = array();
        $limit = constant('PAGINACAO_NORMAL');
        $retorno['pagina']  = $pagina = $this->params('pagina') ? $this->params('pagina') : 1;
        $retorno['quantidadeTags'] = $this->getModel('tag')->getQuantidadeTags(array('evento' => 1));
        $retorno['totalPaginas'] = ceil($retorno['quantidadeTags']/$limit);
        
        if($retorno['totalPaginas'] < $pagina){
        	$this->flashMessenger()->addMessage($this->getMsg('Página inexistente.', false));
        	return $this->redirect()->toRoute('listar-eventos', array('pagina' => 1));
        }
        
        $tags = $this->getModel('Tag')->getEventos($pagina,$limit);
        foreach($tags as $tag){
            
            $tag->imagens = $this->getModel('CapturadaTag')->getCapturadasTag($tag,1,3);
            $tag->quantidadeCapturadas = $this->getModel('CapturadaTag')->getQuantidadeCapturadaTag($tag);
            $retorno['tags'][] = $tag;
        }
        
        return new ViewModel($retorno);
    } 
    
}
